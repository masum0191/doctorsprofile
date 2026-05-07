@props([
    'title' => 'Items',
    'name' => 'items',
    'items' => collect(),
    // schema: [['key'=>'field','label'=>'Label','type'=>'text|number|textarea|checkbox|hidden']]
    'schema' => []
])

@php
    $schemaJson = json_encode($schema, JSON_UNESCAPED_UNICODE);
    $itemsJson = json_encode($items, JSON_UNESCAPED_UNICODE);
@endphp

<div x-data="repeater('{{ $name }}', {!! $schemaJson !!}, {!! $itemsJson !!})"
     class="bg-white shadow rounded-xl p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">{{ $title }}</h2>
        <button type="button" @click="add()"
                class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">Add</button>
    </div>

    <template x-if="rows.length === 0">
        <p class="text-sm text-gray-500">No items yet.</p>
    </template>

    <div class="space-y-4">
        <template x-for="(row, idx) in rows" :key="row.__uuid">
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm text-gray-500">#<span x-text="idx+1"></span></div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="move(idx,-1)" class="text-xs px-2 py-1 border rounded">Up</button>
                        <button type="button" @click="move(idx,1)" class="text-xs px-2 py-1 border rounded">Down</button>
                        <button type="button" @click="remove(idx)" class="text-xs px-2 py-1 border rounded text-red-600">Remove</button>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <template x-for="field in schema" :key="field.key">
                        <div x-show="field.type !== 'hidden'">
                            <label class="block text-sm font-medium mb-1" x-text="field.label ?? field.key"></label>

                            <template x-if="field.type === 'textarea'">
                                <textarea class="w-full rounded border-gray-300"
                                          :name="inputName(field.key)"
                                          x-model="row[field.key]" rows="3"></textarea>
                            </template>

                            <template x-if="field.type === 'checkbox'">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" class="rounded"
                                           :name="inputName(field.key)" value="1"
                                           :checked="row[field.key] == 1"
                                           @change="row[field.key] = $event.target.checked ? 1 : 0">
                                    <span class="text-sm" x-text="field.label ?? field.key"></span>
                                </label>
                            </template>

                            <template x-if="['text','number'].includes(field.type)">
                                <input :type="field.type" class="w-full rounded border-gray-300"
                                       :name="inputName(field.key)" x-model="row[field.key]">
                            </template>
                        </div>
                    </template>

                    {{-- hidden inputs (id etc.) --}}
                    <template x-for="field in schema" :key="field.key + '-hidden'">
                        <template x-if="field.type === 'hidden'">
                            <input type="hidden" :name="inputName(field.key)" x-model="row[field.key]">
                        </template>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>

@once
@push('scripts')
<script>
function uuid(){ return 'xxxx-4xxx-yxxx-xxxx'.replace(/[xy]/g, c=>{
    const r = Math.random()*16|0, v = c==='x'?r:(r&0x3|0x8); return v.toString(16);
}); }

function repeater(baseName, schema, initial) {
    // Normalize features: CSV <-> array (for "features" field in services)
    const normalizeRow = (row) => {
        row = {...row};
        if ('features' in row && Array.isArray(row.features)) {
            row.features = row.features.join(', ');
        }
        // booleans to 0/1 for checkboxes
        if ('verified' in row) row.verified = row.verified ? 1 : 0;
        if ('active' in row) row.active = row.active ? 1 : 0;
        return row;
    };

    return {
        schema,
        rows: (initial || []).map(r => ({__uuid: uuid(), ...normalizeRow(r)})),

        inputName(key) {
            const idx = this.rows.findIndex(r => r.__uuid === this.currentUuid);
            return `${baseName}[${idx}][${key}]`;
        },

        // Proxy: set currentUuid before each render of input
        get currentUuid(){
            return this.__currentUuid;
        },
        set currentUuid(v){
            this.__currentUuid = v;
        },

        // Add/Remove/Move
        add(){
            const blank = schema.reduce((o,f)=> (o[f.key] = (f.type==='checkbox'?0:null), o), {});
            blank.id = null;
            this.rows.push({__uuid: uuid(), ...blank});
        },
        remove(idx){ this.rows.splice(idx,1); },
        move(idx, dir){
            const to = idx + dir;
            if (to < 0 || to >= this.rows.length) return;
            const tmp = this.rows[idx];
            this.rows[idx] = this.rows[to];
            this.rows[to] = tmp;
        },

        // Ensure names bind to correct index on render
        init(){
            this.$watch('rows', () => {}, {deep:true});
            this.$nextTick(() => {
                this.$el.querySelectorAll('input,textarea,select').forEach(el=>{
                    const wrap = el.closest('[x-for]');
                    if (!wrap) return;
                });
            });
        }
    }
}
</script>
@endpush
@endonce
