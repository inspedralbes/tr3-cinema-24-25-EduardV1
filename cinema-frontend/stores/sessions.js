import { defineStore } from 'pinia';

export const useSessionsStore = defineStore('sessions', {
  state: () => ({
    sessions: [
      {
        id: 1,
        date: '2024-05-01',
        time: '18:00',
        isSpecialDay: false,
        movie: {
          title: 'Inception',
          director: 'Christopher Nolan',
          year: '2010',
          plot: 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
          poster: 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'
        }
      },
      {
        id: 2,
        date: '2024-05-02',
        time: '16:00',
        isSpecialDay: true,
        movie: {
          title: 'The Shawshank Redemption',
          director: 'Frank Darabont',
          year: '1994',
          plot: 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
          poster: 'https://m.media-amazon.com/images/M/MV5BNDE3ODcxYzMtY2YzZC00NmNlLWJiNDMtZDViZWM2MzIxZDYwXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_SX300.jpg'
        }
      },
      {
        id: 3,
        date: '2024-05-03',
        time: '20:00',
        isSpecialDay: false,
        movie: {
          title: 'The Dark Knight',
          director: 'Christopher Nolan',
          year: '2008',
          plot: 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
          poster: 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg'
        }
      }
    ],
    tickets: []
  }),
  
  getters: {
    getSessionById: (state) => (id) => {
      return state.sessions.find(session => session.id === id);
    },
    
    getSessionsByDate: (state) => (date) => {
      return state.sessions.filter(session => session.date === date);
    },
    
    getTicketsByEmail: (state) => (email) => {
      return state.tickets.filter(ticket => ticket.userInfo.email === email);
    },
    
    getTodaySession: (state) => {
      const today = new Date().toISOString().split('T')[0];
      return state.sessions.find(session => session.date === today);
    }
  },
  
  actions: {
    addSession(session) {
      const newId = Math.max(0, ...this.sessions.map(s => s.id)) + 1;
      this.sessions.push({
        ...session,
        id: newId
      });
    },
    
    updateSession(updatedSession) {
      const index = this.sessions.findIndex(session => session.id === updatedSession.id);
      if (index !== -1) {
        this.sessions[index] = updatedSession;
      }
    },
    
    deleteSession(id) {
      this.sessions = this.sessions.filter(session => session.id !== id);
    },
    
    addTicket(ticket) {
      this.tickets.push(ticket);
    },
    
    hasTicketsForSession(email, sessionId) {
      return this.tickets.some(
        ticket => ticket.userInfo.email === email && ticket.sessionId === sessionId
      );
    }
  }
});