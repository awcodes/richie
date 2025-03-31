@props([
    'menu' => null,
    'field' => null,
])

<div class="richie-bubble-menu" x-show="isFocused && editor().isActive('table', updatedAt)" x-cloak>
    @foreach($menu->getActions() as $action)
        @php
            $action = $field->getAction($action->getName());
        @endphp
        @if($action->isVisible())
            {{ $action }}
        @endif
    @endforeach
</div>
