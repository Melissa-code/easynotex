import { mount } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import NoteFormComponent from '../../../src/components/note/NoteFormComponent.vue'

// Mock du SpinnerComponent
vi.mock('../../../src/components/shared/SpinnerComponent.vue', () => ({
  default: {
    name: 'SpinnerComponent',
    template: '<div class="spinner-mock">Loading...</div>'
  }
}))

describe('NoteFormComponent - Tests d\'interface', () => {
  const defaultProps = {
    formData: {
      title: '',
      category: '',
      content: '',
      image: null,
      isFavorite: 0
    },
    categories: [
      { id: 1, name: 'Catégorie 1' },
      { id: 2, name: 'Catégorie 2' }
    ],
    selectedFile: null,
    isSubmitting: false,
    errorMessage: ''
  }

  it('devrait afficher tous les champs du formulaire dans une structure correcte', () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    // check the form (data-testid="note-creation-form")
    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    // title input
    const titleInput = form.find('input[placeholder="TITRE DE LA NOTE *"]')
    expect(titleInput.exists()).toBe(true)
    expect(titleInput.attributes('required')).toBeDefined()
    expect(titleInput.attributes('aria-label')).toBe('title_note')

    // category select
    const categorySelect = form.find('select')
    expect(categorySelect.exists()).toBe(true)
    expect(categorySelect.attributes('required')).toBeDefined()

    const options = form.findAll('option')
    expect(options).toHaveLength(3) // Option par défaut + 2 catégories
    expect(options[0].text()).toBe('Sélectionner une catégorie *')
    expect(options[1].text()).toBe('Catégorie 1')
    expect(options[2].text()).toBe('Catégorie 2')

    // Content textarea
    const contentTextarea = form.find('textarea[placeholder="Contenu de la note *"]')
    expect(contentTextarea.exists()).toBe(true)
    expect(contentTextarea.attributes('required')).toBeDefined()
    expect(contentTextarea.attributes('aria-label')).toBe('content_note')

    // Upload image
    const fileInput = form.find('input[type="file"]')
    expect(fileInput.exists()).toBe(true)
    expect(fileInput.attributes('accept')).toBe('image/*')
    expect(fileInput.attributes('aria-label')).toBe('image_upload')

    // Checkbox favori
    const favoriteCheckbox = form.find('input[type="checkbox"]')
    expect(favoriteCheckbox.exists()).toBe(true)
    expect(favoriteCheckbox.attributes('aria-label')).toBe('is_favorite')
  })


  it('devrait afficher les labels corrects et explications', () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    // Label file
    const fileLabel = form.find('label[for="image_upload"]')
    expect(fileLabel.text()).toContain('Choisir une image')

    // Label favori
    const favoriteLabel = form.find('label[for="is_favorite"]')
    expect(favoriteLabel.text()).toBe('Marquer comme favori')

    // Text help textarea
    const helpText = form.find('small')
    expect(helpText.text()).toBe('Appuyez sur Entrée pour ajouter une ligne')

    // Indication required fields
    const requiredText = form.find('small.required-fields')
    expect(requiredText.text()).toBe('* Champs obligatoires')
  })


  it('vérifie les boutons d\'action', () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    //Retour
    const backButton = form.find('button[type="button"]')
    expect(backButton.exists()).toBe(true)
    expect(backButton.text()).toBe('Retour')
    expect(backButton.classes()).toContain('btn-back')

    //Ajouter
    const submitButton = form.find('button[type="submit"]')
    expect(submitButton.exists()).toBe(true)
    expect(submitButton.text()).toBe('Créer')
    expect(submitButton.classes()).toContain('btn-create-note')
    expect(submitButton.attributes('disabled')).toBeUndefined()
  })


  it('devrait afficher le spinner et désactiver le bouton submit avant l\'envoi du formulaire', () => {
    const wrapper = mount(NoteFormComponent, {
      props: {
        ...defaultProps,
        isSubmitting: true
      }
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const submitButton = form.find('button[type="submit"]')
  
    expect(submitButton.attributes('disabled')).toBeDefined()
    expect(submitButton.classes()).toContain('cursor-not-allowed')
    expect(submitButton.text()).toContain('Ajout')
    expect(form.find('.spinner-mock').exists()).toBe(true)
  })


  it('devrait tourner la flèche au clic dessus', async () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const select = form.find('select')
    const arrow = form.find('.transition-transform')

    //initial state 
    expect(arrow.classes()).not.toContain('rotate-180')

    // clic select
    await select.trigger('click')
    expect(arrow.classes()).toContain('rotate-180')
  })


  it('devrait afficher un message d\'erreur', () => {
    const errorMessage = 'Erreur de validation'
    const wrapper = mount(NoteFormComponent, {
      props: {
        ...defaultProps,
        errorMessage
      }
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const errorDiv = form.find('.text-red-500')
    expect(errorDiv.exists()).toBe(true)
    expect(errorDiv.text()).toContain(`ERREUR : ${errorMessage}`)
  })


  it('devrait montrer le nom du fichier sélectionné', () => {
    const selectedFile = { name: 'image.jpg' }
    const wrapper = mount(NoteFormComponent, {
      props: {
        ...defaultProps,
        selectedFile
      }
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const fileLabel = form.find('label[for="image_upload"] span')
    expect(fileLabel.text()).toBe('image.jpg')
  })


  it('devrait ne pas afficher le nom du fichier si aucun fichier n\'est sélectionné', () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const fileLabel = form.find('label[for="image_upload"] span')
    expect(fileLabel.text()).toBe('Choisir une image')
  })

  
  it('devrait montrer l\'icône d\'upload image', () => {
    const wrapper = mount(NoteFormComponent, {
      props: defaultProps
    })

    const form = wrapper.find('[data-testid="note-creation-form"]')
    expect(form.exists()).toBe(true)

    const icon = form.find('i.far.fa-image')
    expect(icon.exists()).toBe(true)
    expect(icon.attributes('style')).toBe('color: rgb(122, 122, 122);')
  })
})