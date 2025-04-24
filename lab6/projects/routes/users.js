const express = require('express');
const router = express.Router();
const User = require('../models/User');

router.get('/register', (req, res) => res.render('auth/register'));
router.post('/register', async (req, res) => {
  const user = new User(req.body);
  await user.save();
  res.redirect('/login');
});

router.get('/login', (req, res) => res.render('auth/login'));
router.post('/login', async (req, res) => {
  const user = await User.findOne({ email: req.body.email });
  if (user && await user.provjeriLozinku(req.body.lozinka)) {
    req.session.user = user;
    res.redirect('/projects');
  } else {
    res.send('Pogrešan email ili lozinka.');
  }
});

router.get('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/login'));
});

module.exports = router;
