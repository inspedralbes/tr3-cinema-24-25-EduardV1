import { format } from 'date-fns';
import { ca as catalan } from 'date-fns/locale';

export const useFormatDate = () => {
  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return format(date, "EEEE, d 'de' MMMM 'de' yyyy", { locale: catalan });
  };

  const formatTime = (time: string) => {
    return time;
  };

  return {
    formatDate,
    formatTime
  };
};