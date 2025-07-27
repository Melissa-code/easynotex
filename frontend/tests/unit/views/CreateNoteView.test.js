import { mount } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import { createRouter, createWebHistory } from 'vue-router'
import CreateNoteView from '../../../src/views/CreateNoteView.vue'
import NoteFormComponent from '../../../src/components/note/NoteFormComponent.vue'

// Mock composables
vi.mock('../../../src/composables/categoriesFetch.js', () => ({
  useCategoriesFetch: () => ({
    categories: [
      { id: 1, name: 'Personnel' },
      { id: 2, name: 'Travail' }
    ]
  })
}))

vi.mock('../../../src/composables/noteFormValidations.js', () => ({
  useNoteFormValidations: () => ({
    isFormValid: () => true,
    validateForm: vi.fn(() => true),
    validateImage: vi.fn(() => true)
  })
}))

vi.mock('../../../src/composables/fileUpload.js', () => ({
  useFileUpload: () => ({
    selectedFile: null,
    handleFileUpload: vi.fn(),
    resetFileInput: vi.fn()
  })
}))

vi.mock('../../../src/composables/notesApi.js', () => ({
  useNotesApi: () => ({
    isSubmitting: false,
    createNote: vi.fn()
  })
}))

vi.mock('../../../src/stores/notifications.js', () => ({
  useNotificationStore: () => ({
    setSuccess: vi.fn()
  })
}))


describe('CreateNoteView tests d\'interface', () => {
  const router = createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/', component: { template: '<div>Home</div>' } },
      { path: '/create', component: CreateNoteView }
    ]
  })


  it('structure générale de la page', () => {
    const wrapper = mount(CreateNoteView, {
      global: {
        plugins: [router]
      }
    })

    const container = wrapper.find('.container-create-note')
    expect(container.exists()).toBe(true)
    expect(container.classes()).toContain('bg-[--yellow-light]')

    const formWrapper = wrapper.find('.create-note')
    expect(formWrapper.exists()).toBe(true)
    expect(formWrapper.classes()).toContain('bg-[--yellow]')
    expect(formWrapper.classes()).toContain('border-[--dark-green]')
    expect(formWrapper.classes()).toContain('rounded-2xl')
  })


  it('devrait afficher le titre de la page', () => {
    const wrapper = mount(CreateNoteView, {
      global: {
        plugins: [router]
      }
    })

    const title = wrapper.find('h1')
    expect(title.exists()).toBe(true)
    expect(title.text()).toBe('Créer une note')
    expect(title.classes()).toContain('text-2xl')
    expect(title.classes()).toContain('font-bold')
    expect(title.classes()).toContain('text-[--dark-green]')
  })


  it('présence du formulaire de note', () => {
    const wrapper = mount(CreateNoteView, {
      global: {
        plugins: [router]
      }
    })

    const noteForm = wrapper.findComponent(NoteFormComponent)
    expect(noteForm.exists()).toBe(true)

    // Checks props passées
    const props = noteForm.props()
    expect(props.formData).toBeDefined()
    expect(props.categories).toBeDefined()
    expect(props.selectedFile).toBeDefined()
    expect(props.isSubmitting).toBeDefined()
    expect(props.errorMessage).toBeDefined()
  })
})