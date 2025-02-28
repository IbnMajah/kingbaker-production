<template>
  <div>
    <Head title="Create Product" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/products">Products</Link>
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
            label="Product Code" 
          />
          <text-input 
            v-model="form.description" 
            :error="form.errors.description" 
            class="pb-8 pr-6 w-full" 
            label="Description"
            type="textarea" 
          />
          <text-input 
            v-model="form.cost_price" 
            :error="form.errors.cost_price" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Cost Price" 
            type="number" 
            step="0.01"
          />
          <text-input 
            v-model="form.selling_price" 
            :error="form.errors.selling_price" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Selling Price" 
            type="number" 
            step="0.01"
          />
          <text-input 
            v-model="form.quantity" 
            :error="form.errors.quantity" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Quantity" 
            type="number"
          />
          <select-input 
            v-model="form.branch_id" 
            :error="form.errors.branch_id" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Branch"
          >
            <option :value="null">Select Branch</option>
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.name }}
            </option>
          </select-input>
          <select-input 
            v-model="form.category_id" 
            :error="form.errors.category_id" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Category"
          >
            <option :value="null">Select Category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button 
            :loading="form.processing" 
            class="btn-kingbaker" 
            type="submit"
          >
            Create Product
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
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  props: {
    branches: {
      type: Array,
      default: () => [],
    },
    categories: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        code: null,
        description: null,
        cost_price: null,
        selling_price: null,
        quantity: 0,
        branch_id: null,
        category_id: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/products')
    },
  },
}
</script>
