<script setup lang="ts">
import { telegramClient } from '@/client/telegram.ts';
import { botsClient } from '@/client/bots.ts';

const bots = ref();
const currentBotId = ref();

const botDialogs = ref();
const currentDialog = ref();

onMounted(() => {
  botsClient.getAll().then(res => {
    bots.value = res;
  });
  telegramClient.getDialogs().then(res => {
    botDialogs.value = res;
  });
});

const formFields = useStorage('tg-form.fields', {
  peer: '',
  message: '',
});

const sendTgMessage = async () => {
  await telegramClient.send(
      formFields.value.peer,
      formFields.value.message,
  );
};
</script>

<template>
  <div class="h-[100vh] flex">
    <div class="h-[100vh] border-solid border-1">
      <Listbox
          v-model="currentDialog"
          :options="bots"
          scroll-height="auto"
          class="h-[100vh]"
      >
        <template #option="{ option, selected, index }">
          <div class="flex gap-2 items-center">
            {{ option.username }}
          </div>
        </template>
      </Listbox>
    </div>
    <div class="h-[100vh] border-solid border-1">
      <Listbox
          v-model="currentDialog"
          :options="botDialogs"
          scroll-height="auto"
          class="h-[100vh]"
      >
        <template #option="{ option, selected, index }">
          <div class="flex gap-2 items-center">
            <template v-if="option.type === 'bot'">
              {{ option.User.first_name }}
              <div class="text-white rounded-[8px] px-2 bg-[#a0f]">bot</div>
            </template>
            <template v-else-if="option.type === 'user'">
              {{ option.User.first_name }}
              <div class="text-white rounded-[8px] px-2 bg-[#0af]">user</div>
            </template>
            <template v-else-if="option.type === 'channel'">
              {{ option.Chat.title }}
              <div class="text-white rounded-[8px] px-2 bg-[#0a0]">channel</div>
            </template>
            <template v-else-if="option.type === 'chat'">
              {{ option.Chat.title }}
              <div class="text-white rounded-[8px] px-2 bg-[#fa0]">chat</div>
            </template>
            <template v-else>
              Unknown
            </template>
          </div>
        </template>
      </Listbox>
      <!--
            <div v-for="dialog in botDialogs">
              {{ dialog.type }}
            </div>
            <pre>{{ botDialogs }}</pre>
      -->
    </div>
    <div class="flex flex-col items-center gap-4 p-6 max-w-md mx-auto bg-white rounded-2xl shadow-lg">
      <h2 class="text-xl font-semibold">Send Telegram Message</h2>

      <div class="w-full">
        <label for="peer" class="block text-sm font-medium text-gray-700">Peer</label>
        <InputText id="peer" v-model="formFields.peer" class="w-full p-2 border rounded-lg" />
      </div>

      <div class="w-full">
        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
        <Textarea id="message" v-model="formFields.message" rows="4" class="w-full p-2 border rounded-lg" />
      </div>

      <Button @click="sendTgMessage"
              class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded-lg">
        Send
      </Button>
    </div>
  </div>
</template>
