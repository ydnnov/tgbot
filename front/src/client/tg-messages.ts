import { http } from '@/http.ts';

export const tgMessagesClient = {
  async send(peer: string, message: string) {
    const response = await http.post('bot/send-message', {
      peer,
      message,
    });
    return response.data.data;
  },
};
