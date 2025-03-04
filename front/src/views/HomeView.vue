<script setup lang="ts">
import { tgMessagesClient } from '@/client/tg-messages.ts';
import { useStorage } from '@vueuse/core';

const formFields = useStorage('tg-form.fields', {
  peer: '',
  message: '',
});

const sendTgMessage = async () => {
  await tgMessagesClient.send(
      formFields.value.peer,
      formFields.value.message
  );
};
</script>

<template>
  <main class="flex flex-col items-center gap-4 p-6 max-w-md mx-auto bg-white rounded-2xl shadow-lg">
    <h2 class="text-xl font-semibold">Send Telegram Message</h2>

    <div class="w-full">
      <label for="peer" class="block text-sm font-medium text-gray-700">Peer</label>
      <InputText id="peer" v-model="formFields.peer" class="w-full p-2 border rounded-lg" />
    </div>

    <div class="w-full">
      <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
      <Textarea id="message" v-model="formFields.message" rows="4" class="w-full p-2 border rounded-lg" />
    </div>

    <Button @click="sendTgMessage" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded-lg">
      Send
    </Button>
  </main>
</template>
