<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BolaoGroup whereUpdatedAt($value)
 */
	class BolaoGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $bolao_group_id
 * @property int $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BolaoGroup $bolaoGroup
 * @property-read \App\Models\Team $team
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereBolaoGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChampionPick whereUserId($value)
 */
	class ChampionPick extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $group_id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property int $match_number
 * @property \Illuminate\Support\Carbon|null $match_date
 * @property int|null $home_score
 * @property int|null $away_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $stage
 * @property-read \App\Models\Team $awayTeam
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\Team $homeTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prediction> $predictions
 * @property-read int|null $predictions_count
 * @property-read \App\Models\Prediction|null $userPrediction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereAwayScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereAwayTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereHomeScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereHomeTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereMatchDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereMatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameMatch whereUpdatedAt($value)
 */
	class GameMatch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameMatch> $matches
 * @property-read int|null $matches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $match_id
 * @property int $home_score
 * @property int $away_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $bolao_group_id
 * @property-read \App\Models\BolaoGroup $bolaoGroup
 * @property-read \App\Models\GameMatch $match
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereAwayScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereBolaoGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereHomeScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prediction whereUserId($value)
 */
	class Prediction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $flag_emoji
 * @property int $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameMatch> $awayMatches
 * @property-read int|null $away_matches_count
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameMatch> $homeMatches
 * @property-read int|null $home_matches_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereFlagEmoji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BolaoGroup> $bolaoGroups
 * @property-read int|null $bolao_groups_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BolaoGroup> $ownedBolaoGroups
 * @property-read int|null $owned_bolao_groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prediction> $predictions
 * @property-read int|null $predictions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

