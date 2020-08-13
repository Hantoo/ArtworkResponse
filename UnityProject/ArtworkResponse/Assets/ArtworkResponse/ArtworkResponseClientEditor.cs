using System;
using System.Collections;
using System.Collections.Generic;
using Unity.EditorCoroutines.Editor;
using UnityEditor;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.UI;

[CustomEditor(typeof(ArtworkResponseClient)), Serializable]
public class ArtworkResponseClientEditor : Editor
{
    void OnEnable()
    {
        
    }

    private ArtworkResponseClient artworkInstance;
    private int toolbarSelected = 0;
    private string urlResponse = "";
    public string prevURL;
    private bool doublecheckURL = false;
    private bool goodConnection = false;
    public override void OnInspectorGUI()
    {
        //DrawDefaultInspector();
        artworkInstance =  (ArtworkResponseClient)target;
       
        toolbarSelected = GUILayout.Toolbar(toolbarSelected, new string[]{"Project Details", "Settings"});
        if (toolbarSelected == 0)
        {
            //Show Details Page
            drawProjectDetails();
        }
        else
        {
            //Show Settings Page
            drawSettings();
        }

        EditorUtility.SetDirty(artworkInstance);
    }

    private void drawSettings()
    {
        
        EditorGUILayout.PrefixLabel("URL");
        artworkInstance.URL = EditorGUILayout.TextField(artworkInstance.URL);
        if( artworkInstance.URL != prevURL )
        {
             EditorCoroutineUtility.StartCoroutine(artworkInstance.checkConnection("/updateProjectResponse.php"), this);
             prevURL = artworkInstance.URL;
        }

        
        if (artworkInstance.connected)
        {
            urlResponse = "Connected!";
            doublecheckURL = false;
        }
        else
        {
            doublecheckURL = true;
            urlResponse = (artworkInstance.getError());
        }
        
        GUI.enabled = false;
        EditorGUILayout.TextField(urlResponse);
        GUI.enabled = true;

        if (artworkInstance.connected)
        {
            EditorGUILayout.PrefixLabel("Project");
            int projectSelectedIndex = EditorGUILayout.Popup(artworkInstance.selectedProjectIndex,
                artworkInstance.getProjectTitles());
            if (projectSelectedIndex != artworkInstance.selectedProjectIndex)
            {
                artworkInstance.selectedProjectIndex = projectSelectedIndex;
                EditorCoroutineUtility.StartCoroutine(artworkInstance.createProjectData("/updateProjectResponse.php?getProject=true&tableName="+(artworkInstance.getSelectedDataTableName().ToLower())+"_data"), this);
            }
            
            EditorGUILayout.Separator();
            
            EditorGUILayout.LabelField("Unique Project Key");
            artworkInstance.unqiueKey = EditorGUILayout.TextField(artworkInstance.unqiueKey);
            
            EditorGUILayout.Separator();
            if (GUILayout.Button("Reload Projects"))
            {
                EditorCoroutineUtility.StartCoroutine(
                    artworkInstance.createProjects("/updateProjectResponse.php?getProjects=true"), this);
            }
            
        }
        EditorGUILayout.Separator();
        if (GUILayout.Button("Ping Server"))
        {
                
            EditorCoroutineUtility.StartCoroutine(
                artworkInstance.ping("/updateProjectResponse.php?Ping=true"), this);
                
            //artworkInstance.createProjects();
        }
        GUI.enabled = false;
        EditorGUILayout.TextField(artworkInstance.pingTime + "ms");
        GUI.enabled = true;
    }

    private void drawProjectDetails()
    {
        if (artworkInstance.connected && artworkInstance.generatedProjectDetails)
        {
            EditorGUILayout.PrefixLabel("Selected Project");
            GUI.enabled = false;
            EditorGUILayout.TextField(artworkInstance.getProjectTitles()[artworkInstance.selectedProjectIndex]);

            EditorGUILayout.Separator();
            EditorGUILayout.LabelField("Project Data");

            for (int i = 0; i < artworkInstance.projectVariablesNames.Length; i++)
            {
                EditorGUILayout.BeginHorizontal();
                EditorGUILayout.LabelField(artworkInstance.projectVariablesNames[i], EditorStyles.boldLabel);
                EditorGUILayout.TextField(artworkInstance.projectVariablesDataTypes[i]);
                EditorGUILayout.EndHorizontal();
            }

            GUI.enabled = true;
        }
        else
        {
            EditorGUILayout.LabelField("Please Connect To artworkResponse site");
        }
    }
    
    private void OnGUI()
    {
        
    }
 
    
}