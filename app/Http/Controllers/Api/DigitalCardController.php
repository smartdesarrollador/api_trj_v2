<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DigitalCardResource;
use App\Models\DigitalCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DigitalCardController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = DigitalCard::with(['personalInfo', 'contactInfo', 'aboutInfo']);
        
        // Si es administrador, puede ver todas las tarjetas, si es autor solo las suyas
        if (Auth::user()->rol !== 'administrador') {
            $query->where('user_id', Auth::id());
        }
        
        // Aplicar filtros si los hay
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('personalInfo', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->has('visibility') && $request->visibility !== 'all') {
            $query->where('is_public', $request->visibility === 'public');
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->leftJoin('digital_card_personal_infos', 'digital_cards.id', '=', 'digital_card_personal_infos.digital_card_id')
                  ->orderBy('digital_card_personal_infos.name', $sortOrder)
                  ->select('digital_cards.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $perPage = $request->get('per_page', 10);
        $digitalCards = $query->paginate($perPage);

        return DigitalCardResource::collection($digitalCards);
    }

    public function store(Request $request): DigitalCardResource
    {
        $validated = $request->validate([
            'personalInfo.name' => 'required|string|max:255',
            'personalInfo.title' => 'nullable|string|max:255',
            'personalInfo.location' => 'nullable|string|max:255',
            'personalInfo.photo' => 'nullable|string|max:255',
            'contact.email' => 'nullable|email|max:255',
            'contact.phone' => 'nullable|string|max:20',
            'contact.linkedin' => 'nullable|url|max:255',
            'contact.website' => 'nullable|url|max:255',
            'contact.twitter' => 'nullable|url|max:255',
            'contact.instagram' => 'nullable|url|max:255',
            'contact.github' => 'nullable|url|max:255',
            'contact.youtube' => 'nullable|url|max:255',
            'contact.tiktok' => 'nullable|url|max:255',
            'contact.whatsapp' => 'nullable|string|max:20',
            'contact.facebook' => 'nullable|url|max:255',
            'about.description' => 'nullable|string',
            'about.skills' => 'nullable|array',
            'about.experience' => 'nullable|integer|min:0|max:50',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $digitalCard = DigitalCard::create([
                    'user_id' => Auth::id(),
                    'is_active' => $validated['is_active'] ?? true,
                    'is_public' => $validated['is_public'] ?? true,
                    'slug' => $this->generateUniqueSlug($validated['personalInfo']['name']),
                ]);

                if (isset($validated['personalInfo'])) {
                    $digitalCard->personalInfo()->create($validated['personalInfo']);
                }

                if (isset($validated['contact'])) {
                    $digitalCard->contactInfo()->create($validated['contact']);
                }

                if (isset($validated['about'])) {
                    $digitalCard->aboutInfo()->create($validated['about']);
                }

                return new DigitalCardResource($digitalCard->load(['personalInfo', 'contactInfo', 'aboutInfo']));
            });
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => 'Error creating digital card: ' . $e->getMessage()
            ]);
        }
    }

    public function show(string $id): DigitalCardResource
    {
        $digitalCard = DigitalCard::with(['personalInfo', 'contactInfo', 'aboutInfo'])
            ->where(function ($query) use ($id) {
                if (is_numeric($id)) {
                    $query->where('id', $id);
                } else {
                    $query->where('slug', $id);
                }
            })
            ->firstOrFail();

        // Verificar permisos después de obtener la tarjeta
        // Los administradores pueden ver cualquier tarjeta
        // Los autores solo pueden ver sus propias tarjetas o tarjetas públicas activas
        if (Auth::user()->rol !== 'administrador') {
            if ($digitalCard->user_id !== Auth::id() && 
                (!$digitalCard->is_public || !$digitalCard->is_active)) {
                abort(404);
            }
        }

        return new DigitalCardResource($digitalCard);
    }

    /**
     * Actualizar tarjeta digital
     * Acepta tanto PUT como POST requests
     * PUT: /api/digital-cards/{id}
     * POST: /api/digital-cards/{id}/update
     */
    public function update(Request $request, DigitalCard $digital_card): DigitalCardResource
    {
        // Verificar permisos: administradores pueden editar cualquier tarjeta, autores solo las suyas
        if (Auth::user()->rol !== 'administrador' && $digital_card->user_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'personalInfo.name' => 'sometimes|required|string|max:255',
            'personalInfo.title' => 'nullable|string|max:255',
            'personalInfo.location' => 'nullable|string|max:255',
            'personalInfo.photo' => 'nullable|string|max:255',
            'contact.email' => 'nullable|email|max:255',
            'contact.phone' => 'nullable|string|max:20',
            'contact.linkedin' => 'nullable|url|max:255',
            'contact.website' => 'nullable|url|max:255',
            'contact.twitter' => 'nullable|url|max:255',
            'contact.instagram' => 'nullable|url|max:255',
            'contact.github' => 'nullable|url|max:255',
            'contact.youtube' => 'nullable|url|max:255',
            'contact.tiktok' => 'nullable|url|max:255',
            'contact.whatsapp' => 'nullable|string|max:20',
            'contact.facebook' => 'nullable|url|max:255',
            'about.description' => 'nullable|string',
            'about.skills' => 'nullable|array',
            'about.experience' => 'nullable|integer|min:0|max:50',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        try {
            return DB::transaction(function () use ($digital_card, $validated) {
                $digital_card->update([
                    'is_active' => $validated['is_active'] ?? $digital_card->is_active,
                    'is_public' => $validated['is_public'] ?? $digital_card->is_public,
                ]);

                if (isset($validated['personalInfo'])) {
                    // Preservar foto actual si no se envía en el request
                    $personalInfoData = $validated['personalInfo'];
                    if (!isset($personalInfoData['photo'])) {
                        $currentPersonalInfo = $digital_card->personalInfo;
                        if ($currentPersonalInfo && $currentPersonalInfo->photo) {
                            $personalInfoData['photo'] = $currentPersonalInfo->photo;
                        }
                    }
                    
                    $digital_card->personalInfo()->updateOrCreate(
                        ['digital_card_id' => $digital_card->id],
                        $personalInfoData
                    );
                    
                    if (isset($validated['personalInfo']['name']) && 
                        $validated['personalInfo']['name'] !== $digital_card->personalInfo?->name) {
                        $digital_card->update([
                            'slug' => $this->generateUniqueSlug($validated['personalInfo']['name'], $digital_card->id)
                        ]);
                    }
                }

                if (isset($validated['contact'])) {
                    $digital_card->contactInfo()->updateOrCreate(
                        ['digital_card_id' => $digital_card->id],
                        $validated['contact']
                    );
                }

                if (isset($validated['about'])) {
                    $digital_card->aboutInfo()->updateOrCreate(
                        ['digital_card_id' => $digital_card->id],
                        $validated['about']
                    );
                }

                return new DigitalCardResource($digital_card->fresh(['personalInfo', 'contactInfo', 'aboutInfo']));
            });
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => 'Error updating digital card: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(DigitalCard $digital_card): JsonResponse
    {
        // Verificar permisos: administradores pueden eliminar cualquier tarjeta, autores solo las suyas
        if (Auth::user()->rol !== 'administrador' && $digital_card->user_id !== Auth::id()) {
            abort(404);
        }
        
        try {
            // Eliminar imágenes asociadas antes de eliminar la tarjeta
            $currentImage = $digital_card->personalInfo?->photo;
            if ($currentImage) {
                $imagePath = public_path($currentImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $digital_card->delete();
            return response()->json(['message' => 'Digital card deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting digital card'], 500);
        }
    }

    public function getBySlug(string $slug): DigitalCardResource
    {
        $digitalCard = DigitalCard::with(['personalInfo', 'contactInfo', 'aboutInfo'])
            ->where('slug', $slug)
            ->where('is_public', true)
            ->where('is_active', true)
            ->firstOrFail();

        return new DigitalCardResource($digitalCard);
    }

    /**
     * Cambiar estado de tarjeta digital
     * Acepta tanto PUT como POST requests
     * PUT: /api/digital-cards/{id}/toggle-status
     * POST: /api/digital-cards/{id}/toggle-status
     */
    public function toggleStatus(Request $request, DigitalCard $digital_card): DigitalCardResource
    {
        // Verificar permisos: administradores pueden cambiar estado de cualquier tarjeta, autores solo las suyas
        if (Auth::user()->rol !== 'administrador' && $digital_card->user_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'is_public' => 'sometimes|boolean',
        ]);

        $digital_card->update($validated);

        return new DigitalCardResource($digital_card->load(['personalInfo', 'contactInfo', 'aboutInfo']));
    }

    public function uploadImage(Request $request, DigitalCard $digital_card): JsonResponse
    {
        // Verificar permisos: administradores pueden subir imagen a cualquier tarjeta, autores solo las suyas
        if (Auth::user()->rol !== 'administrador' && $digital_card->user_id !== Auth::id()) {
            abort(404);
        }

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048', // Max 2MB
        ]);

        try {
            // Crear directorio si no existe
            $uploadPath = public_path('assets/tarjeta/imagen');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file = $validated['image'];
            $filename = 'card_' . $digital_card->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Mover el archivo
            $file->move($uploadPath, $filename);
            
            // Construir la URL relativa
            $imageUrl = 'assets/tarjeta/imagen/' . $filename;

            // Eliminar imagen anterior si existe
            $currentImage = $digital_card->personalInfo?->photo;
            if ($currentImage && file_exists(public_path($currentImage))) {
                unlink(public_path($currentImage));
            }

            // Actualizar la base de datos
            $digital_card->personalInfo()->updateOrCreate(
                ['digital_card_id' => $digital_card->id],
                ['photo' => $imageUrl]
            );

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image_url' => $imageUrl,
                'full_url' => asset($imageUrl)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(DigitalCard $digital_card): JsonResponse
    {
        // Verificar permisos: administradores pueden eliminar imagen de cualquier tarjeta, autores solo las suyas
        if (Auth::user()->rol !== 'administrador' && $digital_card->user_id !== Auth::id()) {
            abort(404);
        }

        try {
            $currentImage = $digital_card->personalInfo?->photo;
            
            if ($currentImage) {
                // Eliminar archivo físico
                $imagePath = public_path($currentImage);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Limpiar campo en base de datos
                $digital_card->personalInfo()->updateOrCreate(
                    ['digital_card_id' => $digital_card->id],
                    ['photo' => null]
                );
            }

            return response()->json([
                'message' => 'Image deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (DigitalCard::where('slug', $slug)
               ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
               ->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
