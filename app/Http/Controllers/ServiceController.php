<?php 

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::where('clinic_id', Auth::id()) // only logged-in clinic's services
            ->get();

        return view('services.index', compact('services'));
    }

    public function create() {
        $clinic = Auth::guard('clinic')->user();
        $specializations = $clinic->specializations;
        
        // Handle case where specializations might be double-encoded or stored as string
        if (is_string($specializations)) {
            $decoded = json_decode($specializations, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $specializations = $decoded;
            }
        }
        
        $specializations = $specializations ?? [];
        
        return view('services.create', compact('specializations')); 
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location_type' => 'required|in:clinic,home,both',
            'animal_type' => 'required|array',
            'animal_type.*' => 'string|max:100',
            'other_animal_type' => 'nullable|string|max:100',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $animals = $request->animal_type;
        if (in_array('Other', $animals) && $request->filled('other_animal_type')) {
            $animals = array_diff($animals, ['Other']);
            $animals[] = $request->other_animal_type;
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $imagePaths[] = $imageFile->store('services', 'public');
                }
            }
        }

        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'location_type' => $request->location_type,
            'animal_type' => implode(',', $animals),
            'clinic_id' => Auth::id(), // link service to logged-in clinic
        ]);

        if (!empty($imagePaths)) {
            $service->images = $imagePaths;
            $service->save();
        }

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function show($id) {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        return view('services.show', compact('service'));
    }

    public function edit($id) {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);
        
        $clinic = Auth::guard('clinic')->user();
        $specializations = $clinic->specializations;
        
        // Handle case where specializations might be double-encoded or stored as string
        if (is_string($specializations)) {
            $decoded = json_decode($specializations, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $specializations = $decoded;
            }
        }
        
        $specializations = $specializations ?? [];

        return view('services.edit', compact('service', 'specializations'));
    }

    public function update(Request $request, $id) {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location_type' => 'required|in:clinic,home,both',
            'animal_type' => 'required|array',
            'animal_type.*' => 'string|max:100',
            'other_animal_type' => 'nullable|string|max:100',
            'existing_images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $animals = $request->animal_type;
        if (in_array('Other', $animals) && $request->filled('other_animal_type')) {
            $animals = array_diff($animals, ['Other']);
            $animals[] = $request->other_animal_type;
        }

        // Keep selected existing images
        $imagePaths = $request->input('existing_images', []);

        // Add new images
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $imageFile) {
                if ($imageFile && $imageFile->isValid()) {
                    $imagePaths[] = $imageFile->store('services', 'public');
                }
            }
        }

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'location_type' => $request->location_type,
            'animal_type' => implode(',', $animals),
            'images' => $imagePaths,
            'clinic_id' => Auth::id(), // ensure still linked to clinic
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy($id) {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    public function toggleAvailability($id)
    {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $service->is_available = !$service->is_available;
        $service->save();

        return redirect()->back()->with('success', 'Service availability updated.');
    }

    public function addHomeSlot(Request $request, $id)
    {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'slot' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $slot = $request->input('slot');

        $slots = $service->home_slots ?? [];
        if (!is_array($slots)) {
            $slots = [];
        }

        if (!in_array($slot, $slots, true)) {
            $slots[] = $slot;
            $service->home_slots = $slots;
            $service->save();
        }

        return redirect()->back()->with('success', 'Home service slot added.');
    }

    public function deleteHomeSlot(Request $request, $id)
    {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'slot' => 'required|string',
        ]);

        $slot = $request->input('slot');

        $slots = $service->home_slots ?? [];
        if (!is_array($slots)) {
            $slots = [];
        }

        $slots = array_values(array_filter($slots, function ($existing) use ($slot) {
            return $existing !== $slot;
        }));

        $service->home_slots = $slots;
        $service->save();

        return redirect()->back()->with('success', 'Home service slot deleted.');
    }

    public function clearHomeSlots($id)
    {
        $service = Service::where('clinic_id', Auth::id())
            ->findOrFail($id);

        $service->home_slots = [];
        $service->save();

        return redirect()->back()->with('success', 'All home service slots cleared.');
    }
}
