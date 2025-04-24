const express = require('express');
const router = express.Router();
const User = require('../models/User');

// REGISTRACIJA - GET
router.get('/register', (req, res) => {
  res.render('auth/register');
});

// REGISTRACIJA - POST
router.post('/register', async (req, res) => {
  const { ime, email, lozinka } = req.body;

  try {
    const postoji = await User.findOne({ email });
    if (postoji) return res.send('Korisnik s tim emailom već postoji.');

    const user = new User({ ime, email, lozinka }); // ⚠️ hashira se automatski u modelu
    await user.save();

    req.session.user = user;
    res.redirect('/projects');
  } catch (err) {
    console.error(err);
    res.send('Greška prilikom registracije: ' + err.message);
  }
});

// PRIJAVA - GET
router.get('/login', (req, res) => {
  res.render('auth/login');
});

// PRIJAVA - POST
router.post('/login', async (req, res) => {
  const { email, lozinka } = req.body;

  try {
    const user = await User.findOne({ email });
    if (!user) return res.send('Pogrešan email ili lozinka.');

    const ok = await user.provjeriLozinku(lozinka);
    if (!ok) return res.send('Pogrešan email ili lozinka.');

    req.session.user = user;
    res.redirect('/projects');
  } catch (err) {
    console.error(err);
    res.send('Greška prilikom prijave: ' + err.message);
  }
});

// ODJAVA
router.get('/logout', (req, res) => {
  req.session.destroy(() => {
    res.redirect('/login');
  });
});

module.exports = router;
