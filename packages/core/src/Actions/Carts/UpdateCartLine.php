<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Facades\DB;
use Payflow\Models\CartLine;

class UpdateCartLine extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  \Payflow\Models\CartLine  $cartLine
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \Payflow\Models\CartLine
     */
    public function execute(
        int $cartLineId,
        int $quantity,
        $meta = null
    ): self {
        DB::transaction(function () use ($cartLineId, $quantity, $meta) {
            $data = [
                'quantity' => $quantity,
            ];

            if ($meta) {
                if (is_object($meta)) {
                    $meta = (array) $meta;
                }
                $data['meta'] = $meta;
            }

            CartLine::whereId($cartLineId)->update($data);
        });

        return $this;
    }
}
