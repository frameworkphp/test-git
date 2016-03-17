<?php
/**
 * {{CLASS_NAME}}.php {{DATE}}
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) {{YEAR}}
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace {{CONTROLLER_NAMESPACE}};

{{USE_NAMESPACE}}

class {{CLASS_NAME}} extends {{BASE_CONTROLLER}}
{
    protected $recordPerPage = {{RECORD_PER_PAGE}};

    public function indexAction()
    {
        $page = (int)$this->request->getQuery('page', 'int', 1);
        $keyword = $this->request->getQuery('q', 'string', '');
        $sort = $this->request->getQuery('sort', 'string', '');
        $dir = $this->request->getQuery('dir', 'string', '');

        // Create url dynamic
        $currentUrl = substr($this->router->getRewriteUri(), 1);
        $queryUrl = '';
        if ($keyword != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'q=' . $keyword;
        }

        // Add keyword parameter
        $keywordIn = {{KEYWORD_IN}};
        $parameter = [
            'keyword' => $keyword,
            'keywordIn' => $keywordIn
        ];

        // Get and add filter in parameter
        $filterTag = [];
{{FILTER_GET}}

{{FILTER_PARAM}}
        // Get list {{VARIABLE_NAME}}s
        ${{VARIABLE_NAME}}s = {{MODEL_NAME}}::get{{FUNCTION_NAME}}s($parameter, '*', $this->recordPerPage, $page, $sort, $dir);

        // Always abort sortBy and sortType
        $orderUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');

        if ($sort != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'sort=' . $sort;
        }

        if ($dir != '') {
            $queryUrl .= ($queryUrl == '' ? '?' : '&') . 'dir=' . $dir;
        }

        $paginateUrl = $currentUrl . $queryUrl . ($queryUrl == '' ? '?' : '&');

        $this->view->setVars([
            'parameter' => $parameter,
            'sort' => $sort,
            'dir' => $dir,
            '{{VARIABLE_NAME}}s' => ${{VARIABLE_NAME}}s,
            'orderUrl' => $orderUrl,
            'pagination' => ${{VARIABLE_NAME}}s,
            'paginateUrl' => $paginateUrl,
            'filterTag' => $filterTag,
{{FILTER_ASSIGN}}
        ]);
        $this->tag->prependTitle('Manager {{VARIABLE_NAME}}');
    }

    public function addAction()
    {
        $formData = [];
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();
                ${{VARIABLE_NAME}} = new {{MODEL_NAME}}();

{{ADD_ASSIGN_PROPERTY}}

                if (${{VARIABLE_NAME}}->create()) {
                    $formData = [];
                    $this->flash->success('Add {{VARIABLE_NAME}} successfully');
                } else {
                    $this->flash->outputMessage('error', ${{VARIABLE_NAME}}->getMessages());
                }
            }
        }

        $this->view->setVars([
            'formData' => $formData,
{{FILTER_ASSIGN}}
        ]);
        $this->tag->prependTitle('Add {{VARIABLE_NAME}}');
    }

    public function editAction($id)
    {
        ${{VARIABLE_NAME}} = {{MODEL_NAME}}::get{{FUNCTION_NAME}}ById($id);

        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $formData = $this->request->getPost();

{{ADD_ASSIGN_PROPERTY}}

                if (${{VARIABLE_NAME}}->update()) {
                    $this->flashSession->success('{{FUNCTION_NAME}} ' . ${{VARIABLE_NAME}}->{{PROPERTY_NAME}} . ' updated.');

                    if ($formData['redirect'] != '') {
                        $redirect = $formData['redirect'];
                    } else {
                        $redirect = '{{REDIRECT_INDEX}}';
                    }
                    $this->response->redirect($redirect . '#_' . $id);
                } else {
                    $this->flash->outputMessage('error', ${{VARIABLE_NAME}}->getMessages());
                }
            }
        }

        $this->view->setVars([
            '{{VARIABLE_NAME}}' => ${{VARIABLE_NAME}},
{{FILTER_ASSIGN}}
            'redirect' => $this->request->getHTTPReferer()
        ]);
        $this->tag->prependTitle('Edit {{VARIABLE_NAME}}');
    }

    public function deleteAction($id)
    {
        $httpRefer = $this->request->getHTTPReferer();
        if ($httpRefer) {
            ${{VARIABLE_NAME}} = {{MODEL_NAME}}::get{{FUNCTION_NAME}}ById($id);

            if (${{VARIABLE_NAME}}->delete()) {
               $this->flashSession->success('Delete {{VARIABLE_NAME}} ' . ${{VARIABLE_NAME}}->{{PROPERTY_NAME}} . ' successfully');
            } else {
                $this->flashSession->outputMessage('error', ${{VARIABLE_NAME}}->getMessages());
            }
        }

        return $this->response->redirect('{{REDIRECT_INDEX}}');
    }

    public function deletesAction()
    {
        if ($this->request->isPost()) {
            $ids = $this->request->getPost('cid');
            if (count($ids) > 0) {
                ${{VARIABLE_NAME}}s = {{MODEL_NAME}}::find('id IN (' . implode(',', $ids) . ')');

                ${{VARIABLE_NAME}}Deleted = [];
                foreach (${{VARIABLE_NAME}}s as ${{VARIABLE_NAME}}) {
                    if (${{VARIABLE_NAME}}->delete()) {
                        ${{VARIABLE_NAME}}Deleted[] = ${{VARIABLE_NAME}}->{{PROPERTY_NAME}};
                    }
                }

                if (count(${{VARIABLE_NAME}}Deleted) > 0) {
                    $this->flashSession->success('{{MODEL_NAME}}s ' . implode(', ', ${{VARIABLE_NAME}}Deleted) . ' deleted.');
                } else {
                    $this->flashSession->error('{{MODEL_NAME}}s need delete not found.');
                }
            }
        }

        return $this->response->redirect('{{REDIRECT_INDEX}}');
    }
}
