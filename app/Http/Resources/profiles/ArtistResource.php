<?php

namespace App\Http\Resources\profiles;

use App\Http\Resources\SongResource;
use App\Http\Resources\AlbumResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\FileManager;
class ArtistResource extends JsonResource
{

    public function toArray($request)
    {
        return [
        'id' => $this->id,
        'type' => 'artist',
        'displayname' => $this->displayname,
        'avatar' => FileManager::asset_path($this->avatar),
        'nb_albums' => $this->featuredAlbums->count(),
        'latest'  => new SongResource ($this->featuredSongs()->orderBy('created_at')->first()),
        'top_tracks'  => SongResource::collection($this->featuredSongs()->withCount('plays')->orderBy('plays_count','desc')->take(5)->get()),
        'songs'  => SongResource::collection($this->ownSongs),
        'plays'  => $this->featuredSongs()->with('plays')->get()->pluck('plays')->collapse()->count(),
        'albums'  => AlbumResource::collection($this->featuredAlbums),
        'nb_followers' => $this->followers->count()
        ];
    }
}
