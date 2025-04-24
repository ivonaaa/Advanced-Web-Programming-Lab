const express = require('express');
const router = express.Router();
const Project = require('../models/Project');
const User = require('../models/User');

// Middleware zaštita – ako korisnik nije prijavljen
function requireLogin(req, res, next) {
  if (!req.session.user) return res.redirect('/login');
  next();
}

// 👤 Projekti gdje je korisnik voditelj
router.get('/moji-projekti', requireLogin, async (req, res) => {
  const projekti = await Project.find({ voditelj: req.session.user._id }).populate('tim.user');
  res.render('projects/moji', { projekti });
});

// 👥 Projekti gdje je korisnik član
router.get('/kao-clan', requireLogin, async (req, res) => {
  const projekti = await Project.find({ 'tim.user': req.session.user._id }).populate('tim.user');
  res.render('projects/clan', { projekti });
});

// 📦 Arhiva projekata
router.get('/arhiva', requireLogin, async (req, res) => {
  const korisnikId = req.session.user._id;
  const projekti = await Project.find({
    arhiviran: true,
    $or: [
      { voditelj: korisnikId },
      { 'tim.user': korisnikId }
    ]
  }).populate('tim.user');
  res.render('projects/arhiva', { projekti });
});

// 📝 Ažuriraj "obavljeni poslovi" (član tima)
router.post('/:id/update-poslovi', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id);
  const jeClan = project.tim.some(t => t.user.toString() === req.session.user._id);
  if (jeClan) {
    project.poslovi = req.body.poslovi;
    await project.save();
  }
  res.redirect(`/projects/${req.params.id}`);
});

// ✅ Dodaj člana tima iz baze korisnika
router.post('/:id/team', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id);
  const user = await User.findOne({ email: req.body.email });
  if (!user) return res.send('❌ Korisnik ne postoji.');

  const većJeClan = project.tim.some(t => t.user.toString() === user._id.toString());
  if (većJeClan) return res.send('⚠️ Korisnik je već član tima.');

  project.tim.push({ user: user._id, role: req.body.role });
  await project.save();
  res.redirect(`/projects/${req.params.id}`);
});

// 🗑 Obriši projekt
router.post('/:id/delete', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id);
  if (project.voditelj.toString() !== req.session.user._id) {
    return res.redirect('/projects');
  }
  await Project.findByIdAndDelete(req.params.id);
  res.redirect('/projects');
});

// 💾 Spremi izmjene projekta (uključujući arhiviran status)
router.post('/:id', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id);
  if (project.voditelj.toString() !== req.session.user._id) {
    return res.redirect('/projects');
  }

  req.body.arhiviran = req.body.arhiviran === 'on';
  await Project.findByIdAndUpdate(req.params.id, req.body);
  res.redirect('/projects');
});

// ✏️ Forma za uređivanje
router.get('/:id/edit', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id);
  if (project.voditelj.toString() !== req.session.user._id) {
    return res.redirect('/projects');
  }
  res.render('projects/edit', { project });
});

// ➕ Forma za novi projekt
router.get('/new', requireLogin, async (req, res) => {
  const users = await User.find();
  res.render('projects/new', { users });
});

// 🔎 Detalji projekta + lista korisnika za dodavanje članova
router.get('/:id', requireLogin, async (req, res) => {
  const project = await Project.findById(req.params.id).populate('voditelj tim.user');
  const korisnici = await User.find();
  res.render('projects/show', { project, korisnici });
});

// 📋 Svi projekti
router.get('/', async (req, res) => {
  const projects = await Project.find().populate('voditelj tim.user');
  res.render('projects/index', { projects });
});

// 🆕 Kreiraj novi projekt
router.post('/', requireLogin, async (req, res) => {
  req.body.arhiviran = req.body.arhiviran === 'on'; // ako koristiš checkbox i kod kreiranja
  const project = new Project(req.body);
  project.voditelj = req.session.user._id;
  await project.save();
  res.redirect('/projects');
});

module.exports = router;
