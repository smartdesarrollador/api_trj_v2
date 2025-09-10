<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DigitalCardResource extends JsonResource
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
            'user_id' => $this->user_id,
            'personal_info' => $this->when($this->personalInfo, [
                'id' => $this->personalInfo?->id,
                'digital_card_id' => $this->personalInfo?->digital_card_id,
                'name' => $this->personalInfo?->name,
                'title' => $this->personalInfo?->title,
                'location' => $this->personalInfo?->location,
                'photo' => $this->personalInfo?->photo,
                'created_at' => $this->personalInfo?->created_at,
                'updated_at' => $this->personalInfo?->updated_at,
            ]),
            'contact_info' => $this->when($this->contactInfo, [
                'id' => $this->contactInfo?->id,
                'digital_card_id' => $this->contactInfo?->digital_card_id,
                'email' => $this->contactInfo?->email,
                'phone' => $this->contactInfo?->phone,
                'linkedin' => $this->contactInfo?->linkedin,
                'website' => $this->contactInfo?->website,
                'twitter' => $this->contactInfo?->twitter,
                'instagram' => $this->contactInfo?->instagram,
                'github' => $this->contactInfo?->github,
                'youtube' => $this->contactInfo?->youtube,
                'tiktok' => $this->contactInfo?->tiktok,
                'whatsapp' => $this->contactInfo?->whatsapp,
                'facebook' => $this->contactInfo?->facebook,
                'created_at' => $this->contactInfo?->created_at,
                'updated_at' => $this->contactInfo?->updated_at,
            ]),
            'about_info' => $this->when($this->aboutInfo, [
                'id' => $this->aboutInfo?->id,
                'digital_card_id' => $this->aboutInfo?->digital_card_id,
                'description' => $this->aboutInfo?->description,
                'skills' => $this->aboutInfo?->skills ?? [],
                'experience' => $this->aboutInfo?->experience,
                'created_at' => $this->aboutInfo?->created_at,
                'updated_at' => $this->aboutInfo?->updated_at,
            ]),
            'is_active' => $this->is_active,
            'is_public' => $this->is_public,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
