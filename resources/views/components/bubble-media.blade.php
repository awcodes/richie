@props([
    'menu' => null,
    'field' => null,
])

<div class="richie-bubble-menu" x-show="isFocused && editor().isActive('media', updatedAt)" x-cloak>
    <span x-text="editor().getAttributes('media', updatedAt).src" class="link-preview"></span>
    @foreach($menu->getActions() as $action)
        @php
            $action = $field->getAction($action->getName());
        @endphp
        @if($action->isVisible())
            {{ $action }}
        @endif
    @endforeach
</div>
