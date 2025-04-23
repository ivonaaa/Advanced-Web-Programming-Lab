const mongoose = require('mongoose');

const teamMemberSchema = new mongoose.Schema({
  name: String,
  role: String
});

const projectSchema = new mongoose.Schema({
  naziv: String,
  opis: String,
  cijena: Number,
  poslovi: String,
  datumPocetka: Date,
  datumZavrsetka: Date,
  tim: [teamMemberSchema]
});

module.exports = mongoose.model('Project', projectSchema);
