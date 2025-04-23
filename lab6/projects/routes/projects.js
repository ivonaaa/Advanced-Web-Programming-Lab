const express = require('express');
const router = express.Router();
const Project = require('../models/Project');

// Svi projekti
router.get('/', async (req, res) => {
  const projects = await Project.find();
  res.render('projects/index', { projects });
});

// Novi projekt - forma
router.get('/new', (req, res) => {
  res.render('projects/new');
});

// Kreiraj projekt
router.post('/', async (req, res) => {
  const project = new Project(req.body);
  await project.save();
  res.redirect('/projects');
});

// Detalji
router.get('/:id', async (req, res) => {
  const project = await Project.findById(req.params.id);
  res.render('projects/show', { project });
});

// Uredi - forma
router.get('/:id/edit', async (req, res) => {
  const project = await Project.findById(req.params.id);
  res.render('projects/edit', { project });
});

// Spremi izmjene
router.post('/:id', async (req, res) => {
  await Project.findByIdAndUpdate(req.params.id, req.body);
  res.redirect('/projects');
});

// Obriši
router.post('/:id/delete', async (req, res) => {
  await Project.findByIdAndDelete(req.params.id);
  res.redirect('/projects');
});

// Dodaj člana tima
router.post('/:id/team', async (req, res) => {
  const project = await Project.findById(req.params.id);
  project.tim.push(req.body);
  await project.save();
  res.redirect(`/projects/${req.params.id}`);
});

module.exports = router;
