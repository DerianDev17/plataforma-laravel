<div class="py-4 px-4 sm:px-6 lg:px-8">

    <x-ui.alert />

    <div>
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Zoom Links</h3>
                    <p class="mt-1 text-sm text-gray-600">
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form>
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <!-- febrero -->
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="email_address" class="block text-sm font-medium text-gray-700">Link Febrero</label>
                                    <x-jet-input wire:model="zoom_febrero" type="text" name="zoom_febrero" id="zoom_febrero" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                    @error('zoom_febrero') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <!-- junio -->
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="email_address" class="block text-sm font-medium text-gray-700">Link Junio</label>
                                    <x-jet-input wire:model="zoom_junio" type="text" name="zoom_junio" id="zoom_junio" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                    @error('zoom_junio') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <!-- julio -->
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="col-span-3 sm:col-span-2">
                                    <label for="email_address" class="block text-sm font-medium text-gray-700">Link Julio</label>
                                    <x-jet-input wire:model="zoom_julio" type="text" name="zoom_julio" id="zoom_julio" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                    @error('zoom_julio') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button wire:click.prevent="saveLinks()" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>
</div>

