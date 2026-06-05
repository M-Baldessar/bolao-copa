<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'avatar_emoji',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Nome de exibição: apelido se definido, senão o nome completo.
     */
    public function displayName(): string
    {
        return $this->nickname ?: $this->name;
    }

    /**
     * Conteúdo do avatar: emoji da seleção favorita, ou a inicial do nome.
     */
    public function avatarContent(): string
    {
        if ($this->avatar_emoji) {
            return $this->avatar_emoji;
        }
        return strtoupper(mb_substr($this->displayName(), 0, 1));
    }

    public function isAvatarEmoji(): bool
    {
        return ! empty($this->avatar_emoji);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function ownedBolaoGroups()
    {
        return $this->hasMany(BolaoGroup::class, 'owner_id');
    }

    public function bolaoGroups()
    {
        return $this->belongsToMany(BolaoGroup::class, 'bolao_group_user')
            ->withPivot('joined_at');
    }
}
