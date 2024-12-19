<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\Avatar\AvatarResource;
use Modules\Auth\Transformers\BankAccount\BankAccountResource;
use Modules\Auth\Transformers\Company\CompanyResource;
use Modules\Auth\Transformers\Guild\GuildResource;
use Modules\Auth\Transformers\MediaAuthorization\MediaAuthorizationResource;
use Modules\Auth\Transformers\NationalCard\NationalCardResource;
use Modules\Device\Transformers\Device\DeviceResource;
use Modules\Order\Transformers\Order\Show\ShowOrderResource;
use Modules\RoleAndPermission\Transformers\Role\RoleResource;
use Modules\Wallet\Transformers\Wallet\WalletResource;
use Modules\Wallet\Transformers\WalletGold\WalletGoldResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
