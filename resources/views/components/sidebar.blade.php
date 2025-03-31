@props([
    'mergeTags' => null,
    'actions' => null,
])
<div
    class="richie-sidebar"
    x-show="sidebarOpen"
    x-bind:class="{
        'focused': isFocused
    }"
>
    @if ($mergeTags)
        @foreach ($mergeTags as $mergeTag)
            <div
                draggable="true"
                x-on:dragstart="$event?.dataTransfer?.setData('mergeTag', @js($mergeTag))"
                class="richie-sidebar-item justify-center"
            >
                &lcub;&lcub; {{ $mergeTag }} &rcub;&rcub;
            </div>
        @endforeach
    @endif

    @if ($actions)
        @foreach ($actions as $action)
            <div
                draggable="true"
                x-on:dragstart="$event?.dataTransfer?.setData('block', @js($action->getName()))"
                class="richie-sidebar-item"
            >
                @if ($action->getIcon())
                    <x-filament::icon :icon="$action->getIcon()" />
                @endif

                {{ $action->getLabel() }}
            </div>
        @endforeach
    @endif
</div>
