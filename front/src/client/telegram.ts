import { http } from '@/http.ts';

export const telegramClient = {

  async sendMessage(
    botId: number,
    peer: string,
    message: string,
  ) {
    const response = await http.post('telegram/send-message', {
      botId,
      peer,
      message,
    });
    return response.data.data;
  },

  async getDialogs(botId: number) {
    const response = await http.get(`telegram/get-dialogs/${botId}`);
    return response.data;
  },

};
