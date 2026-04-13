<div>
    <form wire:submit="upload" class="space-y-2">
        <input type="file" wire:model="file" class="block w-full text-sm text-gray-600 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
        @error('file') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        <button type="submit" class="w-full py-2 px-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg text-sm">
            Upload
        </button>
    </form>
</div>
