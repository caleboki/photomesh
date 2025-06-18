<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
  title: '',
  description: '',
  image: null as File | null,
});

const submit = () => {
  form.post(route('photos.store'), {
    onError: (errors) => {
      // Handle errors, e.g., display them
      console.error('Upload errors:', errors);
    },
    onSuccess: () => {
      // Handle success, e.g., clear form or show success message
      form.reset();
    },
  });
};

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    form.image = target.files[0];
  }
};
</script>

<template>
  <Head title="Upload Photo" />
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Upload New Photo
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" v-model="form.title" id="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" />
                <div v-if="form.errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.title }}</div>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200"></textarea>
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.description }}</div>
              </div>

              <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                <input type="file" @change="handleFileChange" accept="image/*" id="image" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-600 file:text-indigo-700 dark:file:text-indigo-50 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-700" />
                <div v-if="form.errors.image" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ form.errors.image }}</div>
              </div>

              <div class="flex justify-end">
                <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                  Upload Photo
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
