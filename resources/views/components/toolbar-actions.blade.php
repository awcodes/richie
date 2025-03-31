@props([
    'actions' => null,
    'field' => null,
])

<div {{ $attributes }}>
@foreach ($actions as $action)
    @if ($action instanceof \Filament\Actions\ActionGroup)
        @php
            $action->livewire($this);
            $nestedActions = [];
            foreach ($action->getActions() as $nestedAction) {
                $nestedActions[] = $field->getAction($nestedAction->getName());
            }
        @endphp
        <x-filament-actions::group
            :actions="$nestedActions"
            :icon="$action->getIcon()"
            :button="$action->isButton()"
            :label="$action->getLabel()"
            :color="$action->getColor()"
            :size="$action->getSize()"
            :group="$action->getGroup()"
            :icon-position="$action->getIconPosition()"
            :dropdownMaxHeight="$action->getDropdownMaxHeight()"
            :dropdownOffset="$action->getDropdownOffset()"
            :dropdownPlacement="$action->getDropdownPlacement()"
            :dropdownWidth="$action->getDropdownWidth()"
        ></x-filament-actions::group>
    @else
        @php
            $action = $field->getAction($action->getName());
        @endphp
        @if($action->isVisible())
            {{ $action }}
        @endif
    @endif
@endforeach
</div>
