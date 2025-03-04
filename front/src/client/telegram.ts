import { http } from '@/http.ts';

export const telegramClient = {
  async sendMessage(peer: string, message: string) {
    const response = await http.post('telegram/send-message', {
      peer,
      message,
    });
    return response.data.data;
  },
  async getDialogs() {
    const response = await http.get('telegram/get-dialogs');
    return response.data;
  },

};
