@php
    use Filament\Support\Facades\FilamentAsset;use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;$plugin = FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex justify-end flex-1 mb-4">
            <x-filament-actions::actions :actions="$this->getCachedHeaderActions()" class="shrink-0"/>
        </div>
        {{--        @livewire('calendar.filter-component', ['ownerRecord' => $ownerRecord])--}}
        <div class="flex gap-2">
            @if($this->draggableEvents())
                <div class="flex flex-col collapsable-sidebar collapsed-sidebar" id="sidebar">
                    <div class="py-8"></div>

                    <div class="flex flex-col gap-1 px-2 py-4 text-sm text-center text-white bg-white border border-gray-400 shadow-sm grow rounded-t-xl">


                        @php $index = 0 @endphp
                        @foreach ($this->draggableEvents() as $type => $draggableType)
                            <div class="{{ $index? 'mt-5 border-t border-#31c55d pt-2.5':'' }} fi-breadcrumbs-item-label text-sm font-medium text-gray-500 transition duration-75 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">{{ ucfirst($type) }}</div>
                            @php $index++ @endphp
                            @foreach($draggableType as $draggableEvent)

                                @php
                                    if($draggableEvent['start']){
                                        $isDragableClass = 'draggable-disable';
                                    }else{
                                        $isDragableClass = 'cursor-move draggable';
                                    }
                                @endphp
                                <div class="py-0.5 border rounded-md draggable-bg {{$isDragableClass}}" data-event='
                                {"title": "{{ $draggableEvent['title'] }}", "eventable_id": "{{ $draggableEvent['id'] }}", "eventable_type": "{{ $draggableEvent['eventable_type'] }}", "duration": "{{ $draggableEvent['duration'] }}"}
                                '>
                                    <div class="text-left text-xs ml-2">{{ $draggableEvent['title'] }}</div>
                                    @if($draggableEvent['start'])
                                        <div class="text-left text-xs ml-2">Start : {{ \Carbon\Carbon::parse($draggableEvent['start'])->format(user_date_format().' '.user_time_format()) }}</div>
                                        <div class="text-left text-xs ml-2">End : {{ \Carbon\Carbon::parse($draggableEvent['end'])->format(user_date_format().' '.user_time_format()) }}</div>
                                        <div class="text-right text-xs mr-2"><a href="#" style="color: #2c9e4e" wire:click.prevent="onEventSidebarEditClick('{{ $draggableEvent['eventable_type'] }}', {{ $draggableEvent['id'] }})">Edit-> </a></div>
                                    @endif
                                </div>
                            @endforeach

                        @endforeach

                    </div>

                </div>
            @endif
            <div class="grow">
                <div class="filament-fullcalendar" wire:ignore ax-load
                     ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"
                     ax-load-css="{{ FilamentAsset::getStyleHref('filament-fullcalendar-styles', 'saade/filament-fullcalendar') }}"
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
                /*width: 0;*/
                border: transparent !important;
            }

            .draggable-bg {
                background-color: #22C55E;
                color: #1c7236;
            }
            .draggable-disable{
                background-color: rgb(194 200 197 / 20%);
                color: #5e7163;
            }
        </style>
    </x-filament::section>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('toggleSidebar', () => {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.toggle('collapsed-sidebar');
                    console.log('toggled');
                });
            });

        </script>
    @endpush


    <x-filament-actions::modals/>
</x-filament-widgets::widget>
