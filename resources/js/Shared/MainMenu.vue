<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        <div :class="isUrl('') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/daily-records">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('daily-records') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('daily-records') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Daily Records</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/products">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('products') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
          <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('products') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Products</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/users">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('users') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
        <div :class="isUrl('users') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Users</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/categories">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('categories') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <div :class="isUrl('categories') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Categories</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/branches">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('branches') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('branches') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Branches</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('reports') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('reports') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Reports</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
