<script setup lang="ts">
import { telegramClient } from '@/client/telegram.ts';
import { botsClient } from '@/client/bots.ts';

const bots = ref();
const currentBot = ref();

const botDialogs = ref([]);
const currentDialog = ref();

onMounted(async () => {
  bots.value = await botsClient.getAll();
});

watch(() => currentBot.value?.id, async (id) => {
  if (!id) {
    return;
  }
  botDialogs.value = await telegramClient.getDialogs(id);
});

const formFields = useStorage('tg-form.fields', {
  peer: '',
  message: '',
});

const currentPeerId = computed(() => {
  switch (currentDialog.value?.type) {
    case 'user':
      return currentDialog.value.user_id;
    case 'channel':
      return currentDialog.value.channel_id;
    case 'chat':
      return currentDialog.value.chat_id;
  }
});

const sendTgMessage = async () => {
  if (!currentPeerId.value) {
    alert('Invalid recipient');
    return;
  }
  await telegramClient.sendMessage(
      currentBot.value.id,
      currentPeerId.value,
      formFields.value.message,
  );
};
</script>

<template>
  <div class="h-[100vh] flex">
    <div class="h-[100vh] border-solid border-1">
      <Listbox
          v-model="currentBot"
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
              {{ option.User.first_name }} [{{ option.user_id }}]
              <div class="text-white rounded-[8px] px-2 bg-[#a0f]">bot</div>
            </template>
            <template v-else-if="option.type === 'user'">
              {{ option.User.first_name }} [{{ option.user_id }}]
              <div class="text-white rounded-[8px] px-2 bg-[#0af]">user</div>
            </template>
            <template v-else-if="option.type === 'channel'">
              {{ option.Chat.title }} [{{ option.channel_id }}]
              <div class="text-white rounded-[8px] px-2 bg-[#0a0]">channel</div>
            </template>
            <template v-else-if="option.type === 'chat'">
              {{ option.Chat.title }} [{{ option.chat_id }}]
              <div class="text-white rounded-[8px] px-2 bg-[#fa0]">chat</div>
            </template>
            <template v-else>
              Unknown
            </template>
          </div>
        </template>
      </Listbox>
    </div>
    <div class="flex flex-col items-center gap-4 p-6 grow bg-white">
      <h2 class="text-xl font-semibold">Send Telegram Message</h2>

      <div class="w-full">
        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
        <Textarea id="message" v-model="formFields.message" rows="4" class="w-full p-2 border rounded-lg" />
      </div>

      <Button
          @click="sendTgMessage"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded-lg"
          :disabled="!currentPeerId"
      >
        Send
      </Button>
    </div>
  </div>
</template>
