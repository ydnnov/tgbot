import { http } from '@/http.ts';

export const botsClient = {
  async getAll() {
    const response = await http.get('bots');
    return response.data.data;
  },
};
