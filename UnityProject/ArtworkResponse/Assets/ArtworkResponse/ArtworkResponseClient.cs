using System;
using System.Collections;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Text.RegularExpressions;
using System.Threading;
using UnityEditor;
using UnityEngine;
using Unity.EditorCoroutines.Editor;
using UnityEditor.Experimental;
using UnityEngine.Networking;
using Object = System.Object;

[System.Serializable]
public class ArtworkResponseClient : MonoBehaviour
{
    public string URL = "";

    private string response ="";
    private string errorResponse = "";
    private int projectID = -1;
    public float pingTime = 0;
    [HideInInspector]
    public bool connected = false;

    [HideInInspector] public bool generatedProjectDetails = false;
    [SerializeField]
    public string[,] projectTableDetails;
    [SerializeField]

    public string[] projectVariablesNames;
    public string[] projectVariablesDataTypes;
    
    [SerializeField]
    private string[] projectTitles = new string[1];
    //[HideInInspector,SerializeField]
    [SerializeField]
    public int selectedProjectIndex;
    public string unqiueKey;
    public string dataTableName = "";

    
    public void sendData(string[] dataToSend)
    {
        #if UNITY_EDITOR
            EditorCoroutineUtility.StartCoroutine(AddResponse(dataToSend), this);
        #else
            StartCoroutine(AddResponse(dataToSend));
        #endif

    }

    public string[] insertIntoData(Object value, int position, string[] data = null)
    {
        if (data == null)
        {
            data = new string[projectVariablesNames.Length-2];
        }

        if (position >= data.Length)
        {
            Debug.LogError("Position is greater than array size");
            return data;
        }

        string valueToInsert = "";
        if ((value is String))
        {
            value = "\"" + value + "\"";
            //Debug.Log(valueToInsert);
        }
        
        valueToInsert = value.ToString();

        data[position] = valueToInsert;
        return data;
    }

    public IEnumerator AddResponse(string[] data)
    {
        if (data.Length != projectVariablesNames.Length-2)
        {
            if (data.Length > projectVariablesNames.Length-2)
            {
                Debug.LogError("artworkResponse - You are submitting too many data types. Please only submit the amount of data types shown in the inspector.");
            }
            else
            {
                Debug.LogError("artworkResponse - You seem to be missing data. Please make sure to submit values for all data inputs.");
            }
            yield break;
        }
        string baseURL = "/updateProjectResponse.php?dataTable=true&tableName="+(dataTableName.ToLower())+"_data&numOfVariables="+(projectVariablesNames.Length-2).ToString()+"&unqiueKey="+unqiueKey;
        string fullURL = baseURL;
        for (int i = 2; i < projectVariablesNames.Length; i++)
        {
            fullURL = fullURL + "&columnName"+(i-2).ToString()+"=" + projectVariablesNames[i] + "&columnData"+(i-2).ToString()+"=" + data[i-2];
        }
        //Debug.Log("Full URL: "+ fullURL);
        
        #if UNITY_EDITOR
                yield return EditorCoroutineUtility.StartCoroutine(GetResponse(fullURL), this);
        #else
                    StartCoroutine(GetResponse(fullURL));
        #endif
        
    }


    public string[] getProjectTitles()
    {
        return projectTitles;
    }
    public string getError()
    {
        return errorResponse;
    }

   
    public IEnumerator GetResponse(string url_suffix)
    {
        response = "";
        //Debug.Log(URL+url_suffix);
        using (UnityWebRequest www = UnityWebRequest.Get(URL+url_suffix))
        {
            yield return www.SendWebRequest();
 
            if (www.isNetworkError || www.isHttpError)
            {
                errorResponse = (www.error);
            }
            else
            {
                response = (www.downloadHandler.text);
                
            }
        }
    }
    
    

    public IEnumerator ping(string url_suffix)
    {
        
            DateTime time = DateTime.Now;
            yield return EditorCoroutineUtility.StartCoroutine(GetResponse(url_suffix), this);
            DateTime serverRecievedPing = DateTime.Parse(response);
            TimeSpan total = serverRecievedPing - time;
            //total = total + total;
            pingTime = Math.Abs((total.Seconds * 60) + total.Milliseconds);
        

    }
    public IEnumerator createProjects(string url_suffix)
    {
        //Wait for reponse or error to populate
        
        yield return  EditorCoroutineUtility.StartCoroutine(GetResponse(url_suffix), this);
        
        int headerCount = Regex.Matches(response, "<th>").Count;
        int projectCount = Regex.Matches(response, "<tr>").Count;
        if (headerCount == 0 || projectCount == 0)
        {
            Debug.Log("Table Had No Columns");
            Array.Clear(projectVariablesNames, 0 , projectVariablesNames.Length);
            Array.Clear(projectVariablesDataTypes, 0 , projectVariablesDataTypes.Length);
            yield break;
        }
        //Debug.Log("Generating Table For: "+ headerCount + " x "+ projectCount);
        projectTableDetails = new string[headerCount,projectCount];

        //Get Headers For Table 
        int prevVal = 0;
        for (int i = 0; i < headerCount; i++)
        {
            int startValue = response.IndexOf("<th>", prevVal)+4;
            prevVal = startValue;
            int endStartValue = response.IndexOf("</th>", prevVal);
            projectTableDetails[i, 0] = response.Substring(startValue, endStartValue-startValue);
        }
        
        //Popualte For Table 
        prevVal = 0;
        for (int j = 1; j < projectCount; j++) //Start at 1 because the first TR is table headers
        {
            for (int i = 0; i < headerCount; i++)
            {
                
                int startValue = response.IndexOf("<td>", prevVal) + 4;
                prevVal = startValue;
                int endStartValue = response.IndexOf("</td>", prevVal);
                projectTableDetails[i, j] = response.Substring(startValue, endStartValue - startValue);
            }
        }
        
        //Set Variables 
        projectTitles = new string[projectCount-1];
        for (int j = 1, i = 0; j < projectCount; j++, i++) //Start at 1 because the first TR is table headers
        {
            int headerColIndex = 0;
            for (int k = 0; k < headerCount; k++)
            {
                if (projectTableDetails[k, 0].ToLower() == "name")
                {
                    headerColIndex = k;
                    break;
                }
            }

            projectTitles[i] = projectTableDetails[headerColIndex, j];
        }
        
    }

    public string getSelectedDataTableName()
    {
        int headerColIndex = -1;
        
        for (int x = 0; x < projectTableDetails.GetLength(0); x++)
        {
            if (projectTableDetails[x, 0].ToLower() == "projectcode")
            {
                headerColIndex = x;
                break;
            }
        }

        dataTableName = projectTableDetails[headerColIndex, selectedProjectIndex + 1];
        return projectTableDetails[headerColIndex, selectedProjectIndex+1];
    }
    public IEnumerator createProjectData(string url_suffix)
    {
        //Wait for reponse or error to populate
        
        yield return  EditorCoroutineUtility.StartCoroutine(GetResponse(url_suffix), this);
        
        int headerCount = Regex.Matches(response, "<th>").Count;
        int projectCount = Regex.Matches(response, "<tr>").Count;
        if (headerCount == 0 || projectCount == 0)
        {
            Debug.Log("Table Had No Columns");
            Array.Clear(projectVariablesNames, 0 , projectVariablesNames.Length);
            Array.Clear(projectVariablesDataTypes, 0 , projectVariablesDataTypes.Length);
            yield break;
        }
        
        projectVariablesNames = new string[headerCount];
        projectVariablesDataTypes = new string[headerCount];
        //Get Headers For Table 
        int prevVal = 0;
        for (int i = 0; i < headerCount; i++)
        {
            int startValue = response.IndexOf("<th>", prevVal)+4;
            prevVal = startValue;
            int endStartValue = response.IndexOf("</th>", prevVal);
            projectVariablesNames[i] = response.Substring(startValue, endStartValue-startValue);
        }
        
        //Popualte For Table 
        prevVal = 0;
        
        for (int i = 0; i < headerCount; i++)
        {
            int startValue = response.IndexOf("<td>", prevVal) + 4;
            prevVal = startValue;
            int endStartValue = response.IndexOf("</td>", prevVal);
            //Debug.Log("Start: "+ startValue + ", End: "+endStartValue+ ", Len: "+(endStartValue - startValue)+ ", Total Length: "+response.Length+ ": "+response);
            projectVariablesDataTypes[i] = response.Substring(startValue, endStartValue - startValue);
        }
        
        
        
        generatedProjectDetails = true;
        
        /*print2DTable(projectVariablesDetails);*/
        
    }

    public IEnumerator checkConnection(string url_suffix)
    {
        generatedProjectDetails = false;
        //Wait for reponse or error to populate
        yield return EditorCoroutineUtility.StartCoroutine(GetResponse(url_suffix), this);
        if (response.Contains("<p>Connected To artworkResponse</p>"))
        {
            connected = true;
        }
        else
        {
            
            connected = false;
        }
    }

    public void print2DTable(object[,] table)
    {
        
        for (int x = 0; x < table.GetLength(0); x++)
        {
            string columnValue = "";
            for (int y = 0; y < table.GetLength(1); y++)
            {
                columnValue = columnValue + (string) table[x, y] + ", ";
            }  
            
        }
    }
    // Update is called once per frame
    void Update()
    {
        
    }
}

