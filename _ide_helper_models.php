<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Asset
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BasePivot
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BasePivot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasePivot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BasePivot query()
 */
	class BasePivot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ControlInstruction
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ControlInstruction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlInstruction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlInstruction query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class ControlInstruction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Device
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Asset[] $asset
 * @property-read int|null $asset_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device query()
 */
	class Device extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DeviceAsset
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeviceAsset query()
 */
	class DeviceAsset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExcelDemo
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelDemo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelDemo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExcelDemo query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class ExcelDemo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ImageDemo
 *
 * @property string $path
 * @property mixed $path_group
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDemo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDemo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDemo query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class ImageDemo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PictureDemo
 *
 * @property string $path
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PictureDemo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PictureDemo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PictureDemo query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class PictureDemo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserThirdPartyLogin
 *
 * @property-read mixed $created_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserThirdPartyLogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserThirdPartyLogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserThirdPartyLogin query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel recent()
 */
	class UserThirdPartyLogin extends \Eloquent {}
}

