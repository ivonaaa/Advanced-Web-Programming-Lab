const mongoose = require('mongoose');

const teamMemberSchema = new mongoose.Schema({
  user: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  role: String
});

const projectSchema = new mongoose.Schema({
  naziv: String,
  opis: String,
  cijena: Number,
  poslovi: String,
  datumPocetka: Date,
  datumZavrsetka: Date,
  arhiviran: { type: Boolean, default: false },
  voditelj: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  tim: [teamMemberSchema]
});

module.exports = mongoose.model('Project', projectSchema);
