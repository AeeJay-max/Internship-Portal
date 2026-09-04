@extends('application.layout')

@section('step-content')

    <h2 class="text-2xl font-semibold text-blue-900 mb-8">Family Information</h2>

    <form method="POST" action="{{ route('application.family.store') }}">
        @csrf

        <div class="grid md:grid-cols-2 gap-8">

            {{-- Father --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Father Full Name</label>
                <input type="text" name="father_full_name"
                       value="{{ old('father_full_name', $family->father_full_name ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Father Occupation</label>
                <input type="text" name="father_occupation"
                       value="{{ old('father_occupation', $family->father_occupation ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2"
                       placeholder="e.g. Engineer, Teacher, Student">
            </div>

            {{-- Mother --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mother Full Name</label>
                <input type="text" name="mother_full_name"
                       value="{{ old('mother_full_name', $family->mother_full_name ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mother Occupation</label>
                <input type="text" name="mother_occupation"
                       value="{{ old('mother_occupation', $family->mother_occupation ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2"
                       placeholder="e.g. Doctor, Accountant, Student">
            </div>

        </div>

        {{-- Siblings --}}
        @php
            $savedSiblings = old('siblings', $family->siblings ?? []);
            if (is_string($savedSiblings)) {
                $savedSiblings = json_decode($savedSiblings, true) ?? [];
            }
        @endphp

        <div class="mt-10 border-t pt-8"
             x-data="siblingManager({{ json_encode($savedSiblings) }})">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Siblings</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Add each brother or sister</p>
                </div>
                <button type="button" @click="add()"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all"
                        style="background: #011C3E;"
                        onmouseover="this.style.background='#611818';"
                        onmouseout="this.style.background='#011C3E';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Sibling
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(sibling, index) in siblings" :key="index">
                    <div class="grid md:grid-cols-3 gap-3 items-end p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Full Name</label>
                            <input type="text"
                                   :name="'siblings['+index+'][name]'"
                                   x-model="sibling.name"
                                   class="w-full border rounded-lg px-3 py-2 text-sm"
                                   placeholder="e.g. Anna Petrosyan">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Relation</label>
                            <select :name="'siblings['+index+'][relation]'"
                                    x-model="sibling.relation"
                                    class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="brother">Brother</option>
                                <option value="sister">Sister</option>
                            </select>
                        </div>

                        <div class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Occupation</label>
                                <input type="text"
                                       :name="'siblings['+index+'][occupation]'"
                                       x-model="sibling.occupation"
                                       class="w-full border rounded-lg px-3 py-2 text-sm"
                                       placeholder="e.g. Student, Engineer">
                            </div>
                            <button type="button" @click="remove(index)"
                                    class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors mb-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                    </div>
                </template>

                <template x-if="siblings.length === 0">
                    <div class="text-center py-8 text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-lg">
                        No siblings added. Click "+ Add Sibling" to add one, or leave empty if none.
                    </div>
                </template>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="flex justify-between mt-10">
            <a href="{{ route('application.program') }}"
               class="px-6 py-3 rounded-lg border border-gray-300">
                Back
            </a>
            <button type="submit"
                    class="bg-blue-900 text-white px-6 py-3 rounded-lg hover:bg-blue-800">
                Save & Continue
            </button>
        </div>

    </form>

@endsection

@push('scripts')
    <script>
        function siblingManager(saved) {
            return {
                siblings: saved && saved.length ? saved : [],
                add() {
                    this.siblings.push({ name: '', relation: 'brother', occupation: '' });
                },
                remove(index) {
                    this.siblings.splice(index, 1);
                }
            }
        }
    </script>
@endpush
