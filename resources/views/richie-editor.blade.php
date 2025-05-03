@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $mergeTags = $getMergeTags();
    $sidebarActions = $getSidebarActions();
    $customStyles = $getCustomStyles();
    $mentionItems = $getMentionItems();
    $emptyMentionItemsMessage = $getEmptyMentionItemsMessage();
    $mentionItemsPlaceholder = $getMentionItemsPlaceholder();
    $getMentionItemsUsingEnabled = $getMentionItemsUsingEnabled();
    $maxMentionItems = $getMaxMentionItems();
    $mentionTrigger = $getMentionTrigger();
    $mentionDebounce = $getMentionDebounce();
    $mentionSearchStrategy = $getMentionSearchStrategy();
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @if ($customStyles)
        <div
            wire:ignore
            x-data="{}"
            x-load-css="[@js($customStyles)]"
        ></div>
    @endif
    <div
        id="{{ 'richie-wrapper-' . $statePath }}"
        wire:ignore
        wire:partial="richie-component::{{ $getKey() }}"
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('richie', 'awcodes/richie') }}"
        x-data="richie({
            key: @js($getKey()),
            livewireId: @js($this->getId()),
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: true) }},
            statePath: @js($statePath),
            disabled: @js($isDisabled),
            placeholder: @js($getPlaceholder()),
            mergeTags: @js($mergeTags),
            suggestions: @js($getSuggestionsForTiptap()),
            allowedExtensions: @js($getAllowedExtensions()),
            headingLevels: @js($getHeadingLevels()),
            customDocument: @js($getCustomDocument()),
            nodePlaceholders: @js($getNodePlaceholders()),
            showOnlyCurrentPlaceholder: @js($getShowOnlyCurrentPlaceholder()),
            enableInputRules: @js($getEnableInputRules()),
            enablePasteRules: @js($getEnablePasteRules()),
            debounce: @js($getLiveDebounce()),
            linkProtocols: @js($getLinkProtocols()),
            mentionItems: @js($mentionItems),
            emptyMentionItemsMessage: @js($emptyMentionItemsMessage),
            mentionItemsPlaceholder: @js($mentionItemsPlaceholder),
            maxMentionItems: @js($maxMentionItems),
            mentionTrigger: @js($mentionTrigger),
            getMentionItemsUsingEnabled: @js($getMentionItemsUsingEnabled),
            getSearchResultsUsing: async (search) => {
              return await $wire.getMentionsItems(@js($statePath), search)
            },
            mentionDebounce: @js($mentionDebounce),
            mentionSearchStrategy: @js($mentionSearchStrategy),
        })"
        x-bind:class="{
            'fullscreen': fullscreen,
            'display-mobile': viewport === 'mobile',
            'display-tablet': viewport === 'tablet',
            'display-desktop': viewport === 'desktop'
        }"
        x-on:click.away="blurEditor()"
        x-on:focus-editor.window="focusEditor($event)"
        x-on:dragged-merge-tag.stop="insertMergeTag($event)"
        x-on:dragged-block.stop="handleBlockDrop($event)"
        x-on:handle-suggestion.window="handleSuggestion($event)"
        {{
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class([
                    'richie-wrapper',
                    'invalid' => $errors->has($statePath),
                ])
        }}
    >
        <div class="richie-toolbar" x-bind:class="{'focused': isFocused}">
            <x-richie::toolbar-actions class="richie-toolbar-start" :actions="$getToolbar()" :field="$field" />
            <x-richie::toolbar-actions class="richie-toolbar-end" :actions="$getControls()" :field="$field" />
        </div>

        <div class="richie-content">
            <div class="richie-editor-wrapper">
                <div class="richie-bubble-menu-wrapper">
                    <div>
                        @foreach($getBubbleMenus() as $bubbleMenu)
                            <x-dynamic-component :component="$bubbleMenu->getView()" :menu="$bubbleMenu" :field="$field" />
                        @endforeach
                    </div>
                </div>
                <div
                    x-ref="element"
                    {{
                        \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                            ->class([
                                'richie-editor prose dark:prose-invert max-w-none',
                            ])
                    }}
                ></div>
            </div>

            @if (
                (! $isSidebarHidden()) &&
                (! $isDisabled) && (filled($mergeTags) || filled($sidebarActions))
            )
                <x-richie::sidebar :merge-tags="$mergeTags" :actions="$sidebarActions" />
            @endif
        </div>

        @if($shouldShowWordCount())
        <div class="richie-footer">
            <div>
                <p class="text-xs">Word Count: <span x-text="wordCount"></span>
            </div>
        </div>
        @endif
    </div>
</x-dynamic-component>
