<template>
  <div>
    <Head :title="form.name" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-brand-400 hover:text-brand-600" href="/categories">Categories</Link>
        <span class="text-brand-400 font-medium">/</span>
        {{ form.name }}
      </h1>
    </div>
    <trashed-message v-if="category.deleted_at" class="mb-6" @restore="restore"> 
      This category has been deleted. 
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input 
            v-model="form.name" 
            :error="form.errors.name" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Name" 
          />
          <text-input 
            v-model="form.description" 
            :error="form.errors.description" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Description" 
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button 
            v-if="!category.deleted_at" 
            class="text-red-600 hover:underline" 
            tabindex="-1" 
            type="button" 
            @click="destroy"
          >
            Delete Category
          </button>
          <loading-button 
            :loading="form.processing" 
            class="btn-kingbaker ml-auto" 
            type="submit"
          >
            Update Category
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    category: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.category.name,
        description: this.category.description,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/categories/${this.category.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this category?')) {
        this.$inertia.delete(`/categories/${this.category.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this category?')) {
        this.$inertia.put(`/categories/${this.category.id}/restore`)
      }
    },
  },
}
</script>
