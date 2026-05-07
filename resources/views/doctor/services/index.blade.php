@extends('layouts.admin')
@section('title','Manage Services')

@section('content')
<div class="container mx-auto px-6 lg:px-12 py-10" x-data="servicesForm()">
  @if(session('ok'))
    <div class="mb-6 p-3 rounded bg-green-50 text-green-700">{{ session('ok') }}</div>
  @endif

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Services</h1>
    <button @click="add()" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Add Service</button>
  </div>

  <form method="POST" action="{{ route('admin.services.bulk') }}">
    @csrf
    <template x-for="(item, idx) in items" :key="item.key">
      <div class="bg-white rounded-2xl p-6 mb-4 shadow">
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
            <i :class="item.icon || 'ri-stethoscope-fill'" class="text-cyan-600"></i>
          </div>
          <div class="flex-1 grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-1">Title</label>
              <input type="text" class="w-full border rounded px-3 py-2" x-model="item.title" required>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-1">Icon (Remix icon class)</label>
              <input type="text" class="w-full border rounded px-3 py-2" x-model="item.icon" placeholder="ri-heart-pulse-fill">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold mb-1">Description</label>
              <textarea class="w-full border rounded px-3 py-2" rows="3" x-model="item.description"></textarea>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-1">Badge (optional)</label>
              <input type="text" class="w-full border rounded px-3 py-2" x-model="item.badge" placeholder="Featured, New, etc">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-1">Order</label>
              <input type="number" class="w-full border rounded px-3 py-2" x-model.number="item.order">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-semibold mb-2">Features</label>
              <div class="flex gap-2 mb-2">
                <input type="text" class="border rounded px-3 py-2 flex-1" x-model="featureInput" placeholder="Add a feature & press +">
                <button type="button" class="px-3 py-2 bg-gray-800 text-white rounded" @click="addFeature(idx)">+</button>
              </div>
              <div class="flex flex-wrap gap-2">
                <template x-for="(f, fi) in item.features" :key="fi">
                  <span class="px-3 py-1 bg-cyan-50 text-cyan-700 rounded-full text-sm">
                    <span x-text="f"></span>
                    <button type="button" class="ml-1 text-cyan-800" @click="removeFeature(idx, fi)">×</button>
                  </span>
                </template>
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <button type="button" @click="remove(idx)" class="px-3 py-2 bg-red-50 text-red-600 rounded">Delete</button>
          </div>
        </div>

        {{-- Hidden inputs to send to backend --}}
        <input type="hidden" name="items[@{{ idx }}][id]"        :value="item.id ?? ''">
        <input type="hidden" name="items[@{{ idx }}][title]"     :value="item.title">
        <input type="hidden" name="items[@{{ idx }}][icon]"      :value="item.icon">
        <input type="hidden" name="items[@{{ idx }}][badge]"     :value="item.badge">
        <input type="hidden" name="items[@{{ idx }}][order]"     :value="item.order">
        <input type="hidden" name="items[@{{ idx }}][description]" :value="item.description">
        <template x-for="(f, fi) in item.features" :key="'hidden-'+fi">
          <input type="hidden" :name="`items[${idx}][features][${fi}]`" :value="f">
        </template>
      </div>
    </template>

    <div class="mt-6">
      <button class="px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Save All</button>
    </div>
  </form>
</div>

<script>
function servicesForm() {
  return {
    items: @json($services->map(fn($s)=>[
      'id'=>$s->id,'key'=>$s->id,
      'title'=>$s->title,'icon'=>$s->icon,'badge'=>$s->badge,
      'description'=>$s->description,'order'=>$s->order_column,
      'features'=>$s->features ?? []
    ])),
    featureInput: '',
    add(){
      this.items.push({
        id:null, key:'new-'+Date.now(),
        title:'', icon:'ri-stethoscope-fill', badge:'',
        description:'', order: (this.items.length+1)*10,
        features:[]
      });
    },
    remove(i){ this.items.splice(i,1); },
    addFeature(i){
      const val = this.featureInput?.trim();
      if (val) { this.items[i].features.push(val); this.featureInput=''; }
    },
    removeFeature(i, fi){ this.items[i].features.splice(fi,1); },
  }
}
</script>
@endsection
