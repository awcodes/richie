document.addEventListener('livewire:init', () => {
    const findClosestLivewireComponent = (el) => {
        let closestRoot = Alpine.findClosest(el, (i) => i.__livewire)

        if (!closestRoot) {
            throw 'Could not find Livewire component in DOM tree'
        }

        return closestRoot.__livewire
    }

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effects }) => {
            effects.dispatches?.forEach((dispatch) => {
                if (!dispatch.params?.awaitRichieComponent) {
                    return
                }

                let els = Array.from(
                    component.el.querySelectorAll(
                        `[wire\\:partial="richie-component::${dispatch.params.awaitRichieComponent}"]`,
                    ),
                ).filter((el) => findClosestLivewireComponent(el) === component)

                if (els.length === 1) {
                    return
                }

                if (els.length > 1) {
                    throw `Multiple richie components found with key [${dispatch.params.awaitRichieComponent}].`
                }

                window.addEventListener(
                    `richie-component-${component.id}-${dispatch.params.awaitRichieComponent}-loaded`,
                    () => {
                        window.dispatchEvent(
                            new CustomEvent(dispatch.name, {
                                detail: dispatch.params,
                            }),
                        )
                    },
                    { once: true },
                )
            })
        })
    })
})
