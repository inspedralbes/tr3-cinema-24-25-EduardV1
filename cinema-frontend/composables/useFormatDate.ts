import { format } from 'date-fns';
import { ca } from 'date-fns/locale/ca';

export const useFormatDate = () => {
  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return format(date, "EEEE, d 'de' MMMM 'de' yyyy", { locale: ca });
  };

  const formatTime = (time: string) => {
    return time;
  };

  return {
    formatDate,
    formatTime
  };
};