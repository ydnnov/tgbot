<script setup lang="ts">
import { tgMessagesClient } from '@/client/tg-messages.ts';

const sendTgMessage = async () => {
  await tgMessagesClient.send(
      '@ydnnov',
      'qweasdzxc12345',
  );
};
</script>

<template>
  <main>
    <Button @click="sendTgMessage">Send</Button>
  </main>
</template>
