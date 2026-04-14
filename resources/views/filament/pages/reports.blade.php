<x-filament-panels::page>
    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
        :data="$this->getWidgetData()"
    />

    <x-filament-widgets::widgets
        :widgets="$this->getFooterWidgets()"
        :columns="$this->getFooterWidgetsColumns()"
        :data="$this->getWidgetData()"
    />
</x-filament-panels::page>
