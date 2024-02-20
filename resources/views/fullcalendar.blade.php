@php
    $plugin = \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex justify-end flex-1 mb-4">
            <x-filament-actions::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />
        </div>
        @livewire('calendar.filter-component', ['ownerRecord' => $ownerRecord])
        <div class="flex gap-2">
            <div class="flex flex-col collapsable-sidebar collapsed-sidebar" id="sidebar">
                <div class="py-8"></div>
                <div class="flex flex-col gap-1 px-2 py-4 text-sm text-center text-white bg-white border border-gray-400 shadow-sm grow rounded-t-xl">
                    @if($this->draggableEvents())
                        @foreach ($this->draggableEvents() as $draggableEvent)
                            <div class="cursor-move py-0.5 border rounded-md draggable" data-event='{"title": "{{ $draggableEvent['title'] }}", "eventable_type": "{{ $draggableEvent['eventable_type'] }}"}'>{{ $draggableEvent['title'] }}</div>
                        @endforeach
                    @endif
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
    @push('scripts')
        <script>
            // window.addEventListener('toggleSidebar', function() {
            //     const sidebar = document.getElementById('sidebar');
            //     // function toggleSidebar() {
            //     sidebar.classList.toggle('collapsed-sidebar');
            //     console.log('toggled');
            //     // setTimeout(() => {
            //     //     calendar.updateSize();
            //     // }, 300);
            //     // }
            // });
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('toggleSidebar', () => {
                    const sidebar = document.getElementById('sidebar');
                        sidebar.classList.toggle('collapsed-sidebar');
                    console.log('toggled');
                });
            });

            // Livewire.on('toggleSidebar', () => {
            //     const sidebar = document.getElementById('sidebar');
            //     sidebar.classList.toggle('collapsed-sidebar');
            //     console.log('toggled');
            // });
        </script>
    @endpush


    <x-filament-actions::modals />
</x-filament-widgets::widget>
