@props([
    'menu' => null,
    'field' => null,
])

<div class="richie-bubble-menu" x-show="isFocused && editor().isActive('link', updatedAt)" x-cloak>
    <span x-text="editor().getAttributes('link', updatedAt).href" class="link-preview"></span>
    @foreach($menu->getActions() as $action)
        @php
            $action = $field->getAction($action->getName());
        @endphp
        @if($action->isVisible())
            {{ $action }}
        @endif
    @endforeach
</div>
