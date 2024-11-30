<div>
    @livewire(
        $getContent(),
        [
            'record' => $getRecord(),
        ],
        key('payflow_livewire_'.$getContentName())
    )
</div>