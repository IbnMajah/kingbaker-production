<template>
  <div>
    <Head title="Create Branch" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/branches">Branches</Link>
      <span class="text-brand-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input 
            v-model="form.name" 
            :error="form.errors.name" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Name" 
          />
          <text-input 
            v-model="form.code" 
            :error="form.errors.code" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Code" 
          />
          <text-input 
            v-model="form.address" 
            :error="form.errors.address" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Address" 
          />
          <text-input 
            v-model="form.phone" 
            :error="form.errors.phone" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Phone" 
          />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button 
            :loading="form.processing" 
            class="btn-kingbaker" 
            type="submit"
          >
            Create Branch
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        code: '',
        address: '',
        phone: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/branches')
    },
  },
}
</script>
