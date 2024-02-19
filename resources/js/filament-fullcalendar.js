import { Calendar } from '@fullcalendar/core'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import multiMonthPlugin from '@fullcalendar/multimonth'
import scrollGridPlugin from '@fullcalendar/scrollgrid'
import timelinePlugin from '@fullcalendar/timeline'
import adaptivePlugin from '@fullcalendar/adaptive'
import resourcePlugin from '@fullcalendar/resource'
import resourceDayGridPlugin from '@fullcalendar/resource-daygrid'
import resourceTimelinePlugin from '@fullcalendar/resource-timeline'
import rrulePlugin from '@fullcalendar/rrule'
import momentPlugin from '@fullcalendar/moment'
import momentTimezonePlugin from '@fullcalendar/moment-timezone'
import locales from '@fullcalendar/core/locales-all'

export default function fullcalendar({
    locale,
    plugins,
    schedulerLicenseKey,
    timeZone,
    config,
    editable,
    selectable,
}) {
    return {
        init() {
            let event = null;
            /** @type Calendar */
            const calendar = new Calendar(this.$el, {
                customButtons: {
                    toggleSidebar: {
                      text: 'sidebar',
                      click: function() {
                        toggleSidebar();
                      }
                    }
                  },
                headerToolbar: {
                    'left': 'prev,next today, toggleSidebar',
                    'center': 'title',
                    'right': 'dayGridMonth,dayGridWeek,dayGridDay',
                },
                plugins: plugins.map(plugin => availablePlugins[plugin]),
                locale,
                schedulerLicenseKey,
                timeZone,
                editable,
                selectable,
                droppable: true,
                ...config,
                locales,
                events: (info, successCallback, failureCallback) => {
                    this.$wire.fetchEvents({ start: info.startStr, end: info.endStr, timezone: info.timeZone })
                        .then(successCallback)
                        .catch(failureCallback)
                },
                eventClick: ({ event, jsEvent }) => {
                    jsEvent.preventDefault()

                    if (event.url) {
                        const isNotPlainLeftClick = e => (e.which > 1) || (e.altKey) || (e.ctrlKey) || (e.metaKey) || (e.shiftKey)
                        return window.open(event.url, (event.extendedProps.shouldOpenUrlInNewTab || isNotPlainLeftClick(jsEvent)) ? '_blank' : '_self')
                    }

                    this.$wire.onEventClick(event)
                },
                eventDrop: async ({ event, oldEvent, relatedEvents, delta, revert }) => {
                    const shouldRevert = await this.$wire.onEventDrop(event, oldEvent, relatedEvents, delta)

                    if (typeof shouldRevert === 'boolean' && shouldRevert) {
                        revert()
                    }
                },
                eventResize: async ({ event, oldEvent, relatedEvents, startDelta, endDelta, revert }) => {
                    const shouldRevert = await this.$wire.onEventResize(event, oldEvent, relatedEvents, startDelta, endDelta)

                    if (typeof shouldRevert === 'boolean' && shouldRevert) {
                        revert()
                    }
                },
                dateClick: ({ dateStr, allDay, view }) => {
                    if (!selectable) return;
                    this.$wire.onDateSelect(dateStr, null, allDay, view)
                },
                select: ({ startStr, endStr, allDay, view }) => {
                    console.log(startStr);
                    if (!selectable) return;
                    this.$wire.onDateSelect(startStr, endStr, allDay, view)
                },

                drop: (dropInfo) => {
                    // console.log(dropInfo.event);
                    //console.log(dropInfo);
                    event = dropInfo.draggedEl.dataset.event;
                    // console.log(event);
                    this.$wire.onDrop(dropInfo, event)
                    calendar.updateSize();
                    //event.id = 'deletable';

                },
                eventReceive: (info) => {
                    event = info.event;
                }
            })

            calendar.render();
             // External dragging setup
            const draggableElements = document.querySelectorAll('.draggable');

            // Loop through each draggable element and apply Draggable functionality
            draggableElements.forEach((draggableEl) => {
                new Draggable(draggableEl, {
                    eventData: function(eventEl) {
                        return {
                            title: eventEl.innerText.trim(),
                        };
                    },
                    // Optionally customize other draggable options here
                });
            });

            const sidebar = document.getElementById('sidebar');

            function toggleSidebar() {
                sidebar.classList.toggle('collapsed-sidebar');
                setTimeout(() => {
                    calendar.updateSize();
                }, 300);
            }

            window.addEventListener('filament-fullcalendar--refresh', () => {
                calendar.refetchEvents();
                calendar.updateSize();
                if(event != null)
                {
                    event.remove();
                }
                event = null;
              });
        },
    }
}



const availablePlugins = {
    'interaction': interactionPlugin,
    'dayGrid': dayGridPlugin,
    'timeGrid': timeGridPlugin,
    'list': listPlugin,
    'multiMonth': multiMonthPlugin,
    'scrollGrid': scrollGridPlugin,
    'timeline': timelinePlugin,
    'adaptive': adaptivePlugin,
    'resource': resourcePlugin,
    'resourceDayGrid': resourceDayGridPlugin,
    'resourceTimeline': resourceTimelinePlugin,
    'rrule': rrulePlugin,
    'moment': momentPlugin,
    'momentTimezone': momentTimezonePlugin,
}
