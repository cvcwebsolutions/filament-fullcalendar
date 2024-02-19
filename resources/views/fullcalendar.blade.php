@php
    $plugin = \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex justify-end flex-1 mb-4">
            <x-filament-actions::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />
        </div>
        <div class="flex gap-2">
            <div class="flex flex-col collapsable-sidebar collapsed-sidebar" id="sidebar">
                <div class="py-8"></div>
                <div class="flex flex-col gap-1 px-2 py-4 text-sm text-center text-white bg-white border border-gray-400 shadow-sm grow rounded-t-xl">
                    @foreach ($this->draggableEvents() as $draggableEvent)
                        @php
                            $draggableEventColor = '#D97706';
                            $draggableEventableType = str_replace("\\","\\\\", $draggableEvent->instance_type);
                        @endphp
                        <div class="cursor-move py-0.5 border rounded-md draggable" data-event='{"title": "{{ $draggableEvent->name }}", "description": "{{ $draggableEvent->description }}", "color": "{{ $draggableEventColor }}", "eventable_type": "{{ $draggableEventableType }}", "eventable_id": "{{ $draggableEvent->id }}", "duration": "{{ $draggableEvent->duration }}" }'>{{ $draggableEvent->name }}</div>
                    @endforeach
                </div>
            </div>
            <div class="grow">
                <div class="filament-fullcalendar" wire:ignore ax-load
                    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"
                    ax-load-css="{{ \Filament\Support\Facades\FilamentAsset::getStyleHref('filament-fullcalendar-styles', 'saade/filament-fullcalendar') }}"
                    x-ignore x-data="fullcalendar({
                        locale: @js($plugin->getLocale()),
                        plugins: @js($plugin->getPlugins()),
                        schedulerLicenseKey: @js($plugin->getSchedulerLicenseKey()),
                        timeZone: @js($plugin->getTimezone()),
                        config: @js($plugin->getConfig()),
                        editable: @json($plugin->isEditable()),
                        selectable: @json($plugin->isSelectable()),
                    })">
                </div>
            </div>
        </div>


        <style>
            .collapsable-sidebar {
                width: 250px;
                transition: width 0.3s ease;
                overflow-x: hidden;
            }

            .collapsed-sidebar {
                width: 0;
                border: transparent !important;
            }

            .draggable{
                background-color: #D97706;

            }
        </style>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
