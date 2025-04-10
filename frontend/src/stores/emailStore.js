import { defineStore } from 'pinia';

export const useEmailStore = defineStore('email', {
  state: () => ({
    inboxCount: 0,
    sentCount: 0,
    draftCount: 0,
    spamCount: 0,
  }),
  actions: {
    setInboxCount(count) {
      this.inboxCount = count;
    },
    setSentCount(count) {
      this.sentCount = count;
    },
    setDraftCount(count) {
      this.draftCount = count;
    },
    setSpamCount(count) {
      this.spamCount = count;
    },
    incrementInboxCount() {
      this.inboxCount++;
    },
    incrementSentCount() {
      this.sentCount++;
    },
    incrementDraftCount() {
      this.draftCount++;
    },
    incrementSpamCount() {
      this.spamCount++;
    },
  },
});
