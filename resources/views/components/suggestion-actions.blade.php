@props([
    'actions' => null,
])

<div {{ $attributes }}>
@foreach ($actions as $action)
    @if ($action instanceof \Filament\Actions\ActionGroup)
        <div class="richie-suggestion-group">
            <div class="text-xs">
                {{ $action->getLabel() }}
            </div>
            @foreach($action->getActions() as $action)
                @if($action->isVisible())
                    {{ $action }}
                @endif
            @endforeach
        </div>
    @else
        @if($action->isVisible())
            {{ $action }}
        @endif
    @endif
@endforeach
</div>
