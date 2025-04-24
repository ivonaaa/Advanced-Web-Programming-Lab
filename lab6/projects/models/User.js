const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const userSchema = new mongoose.Schema({
  ime: String,
  email: { type: String, unique: true },
  lozinka: String
});

userSchema.pre('save', async function (next) {
  if (!this.isModified('lozinka')) return next();
  this.lozinka = await bcrypt.hash(this.lozinka, 12);
  next();
});

userSchema.methods.provjeriLozinku = async function (inputLozinka) {
  return await bcrypt.compare(inputLozinka, this.lozinka);
};

module.exports = mongoose.model('User', userSchema);