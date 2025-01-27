
const defaultState = {
  bots: [],
  userId: '',
};

const actions = {
};

const mutations = {
  BOTS_ADDED: (state, bots) => {
    state.bots = bots;
  },
  setAllBotsData: (state, bots) => {
    state.bots = bots;
  },
  setUserId: (state, userId) => {
    state.userId = userId;
  }
};

const getters = {
  bots: state => state.bots,
  userId: state => state.userId,
};

export default {
  state: defaultState,
  getters,
  actions,
  mutations,
};
